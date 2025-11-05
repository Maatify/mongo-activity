<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 13:17
 * Project: maatify-mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Resolver;

use BackedEnum;
use DateTimeImmutable;
use Maatify\Common\Pagination\DTO\PaginationDTO;
use Maatify\Common\Pagination\DTO\PaginationResultDTO;
use Maatify\Common\Pagination\Helpers\PaginationHelper;
use Maatify\MongoActivity\Contract\ActivityLogTypeInterface;
use Maatify\MongoActivity\Contract\AppLogModuleInterface;
use Maatify\MongoActivity\Contract\UserLogRoleInterface;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use RuntimeException;

/**
 * 🔎 Responsible for determining which collection a date range belongs to.
 * Ensures strict separation between active and archived quarters.
 */
final class ActivityPeriodResolver
{
    public function __construct(
        private readonly Client $client,
        private readonly string $activeDb,
        private readonly string $archiveDb,
        private readonly string $activeCollection = 'user_activities',
    ) {}

    /**
     * Returns the target collection name for a given date range.
     * Throws an exception if the range spans across periods.
     */
    public function resolve(?DateTimeImmutable $from, ?DateTimeImmutable $to): array
    {
        $now = new DateTimeImmutable('now');
        $sixMonthsAgo = $now->modify('-6 months');

        $from ??= $now;
        $to ??= $now;

        // ⚠️ لو التاريخ داخل اخر 6 شهور -> Active
        if ($to > $sixMonthsAgo) {
            // لو الفتره بالكامل جوه اخر 6 شهور
            if ($from > $sixMonthsAgo) {
                return [
                    'database' => $this->activeDb,
                    'collection' => $this->activeCollection,
                    'type' => 'active',
                ];
            }

            throw new RuntimeException('⛔ The selected range crosses into an archived period.');
        }

        // ✅ غير كده -> أرشيف محدد
        $quarterName = $this->quarterCollection($to);
        return [
            'database' => $this->archiveDb,
            'collection' => $quarterName,
            'type' => 'archive',
        ];
    }

    /**
     * Calculates the archive collection name based on date.
     * Example: user_activities_archive_2025_0406
     */
    private function quarterCollection(DateTimeImmutable $date): string
    {
        $month = (int)$date->format('m');
        $year = (int)$date->format('Y');

        $ranges = [
            [1, 3],
            [4, 6],
            [7, 9],
            [10, 12],
        ];

        foreach ($ranges as [$start, $end]) {
            if ($month >= $start && $month <= $end) {
                return sprintf('user_activities_archive_%04d_%02d%02d', $year, $start, $end);
            }
        }

        throw new RuntimeException('Invalid month value for quarter mapping.');
    }

    /**
     * 🧠 Main entry: perform a full search in the correct collection automatically.
     */
    public function search(
        ?int $userId = null,
        (UserLogRoleInterface&BackedEnum)|null $role = null,
        ?int $refId = null,
        (AppLogModuleInterface&BackedEnum)|null $module = null,
        (ActivityLogTypeInterface&BackedEnum)|null $type = null,
        ?string $keyword = null,
        ?string $from = null,
        ?string $to = null,
        int $page = 1,
        int $perPage = 20,
        string $sortOrder = 'desc'
    ): PaginationResultDTO {
        $fromDate = $from ? new DateTimeImmutable($from) : null;
        $toDate   = $to ? new DateTimeImmutable($to) : null;

        // 🎯 Determine which collection to query
        $period = $this->resolvePeriod($fromDate, $toDate);
        $collection = $this->client
            ->selectCollection($period['database'], $period['collection']);

        // 🧮 Build filters
        $filter = [];

        if ($userId !== null) {
            $filter['user_id'] = $userId;
        }
        if ($role !== null) {
            $filter['role'] = $role->value;
        }
        if ($refId !== null) {
            $filter['ref_id'] = $refId;
        }
        if ($module !== null) {
            $filter['module'] = $module->value;
        }
        if ($type !== null) {
            $filter['type'] = $type->value;
        }

        // 🕒 Date range
        if ($fromDate !== null || $toDate !== null) {
            $dateFilter = [];
            if ($fromDate !== null) {
                $dateFilter['$gte'] = new UTCDateTime($fromDate->getTimestamp() * 1000);
            }
            if ($toDate !== null) {
                $dateFilter['$lte'] = new UTCDateTime($toDate->getTimestamp() * 1000);
            }
            $filter['created_at'] = $dateFilter;
        }

        // 🔍 Keyword search
        if (!empty($keyword)) {
            $filter['$or'] = [
                ['description' => ['$regex' => $keyword, '$options' => 'i']],
                ['action' => ['$regex' => $keyword, '$options' => 'i']],
            ];
        }

        // 📄 Pagination
        $sortDirection = strtolower($sortOrder) === 'asc' ? 1 : -1;
        $skip = ($page - 1) * $perPage;

        $cursor = $collection->find(
            $filter,
            [
                'limit' => $perPage,
                'skip'  => $skip,
                'sort'  => ['created_at' => $sortDirection],
            ]
        );

        $data = $cursor->toArray();
        $total = $collection->countDocuments($filter);

        // 🧮 Build Pagination DTO
        $paginationArray = PaginationHelper::paginate(
            items: range(1, $total),
            page: $page,
            perPage: $perPage
        )['pagination'];

        $pagination = PaginationDTO::fromArray($paginationArray);

        // 🧩 Return standardized result
        return new PaginationResultDTO(
            data: $data,
            pagination: $pagination,
            meta: [
                'collection' => $period['collection'],
                'period_type' => $period['type'],
                'filters' => array_filter([
                    'user_id' => $userId,
                    'role' => $role?->value,
                    'ref_id' => $refId,
                    'module' => $module?->value,
                    'type' => $type?->value,
                    'keyword' => $keyword,
                    'from' => $from,
                    'to' => $to,
                ]),
            ]
        );

    }

    /**
     * ⚙️ Strictly determine the correct period (active or one archive quarter)
     */
    private function resolvePeriod(?DateTimeImmutable $from, ?DateTimeImmutable $to): array
    {
        $now = new DateTimeImmutable('now');
        $sixMonthsAgo = $now->modify('-6 months');

        $from ??= $now;
        $to ??= $now;

        // داخل آخر 6 شهور → active
        if ($to > $sixMonthsAgo) {
            if ($from > $sixMonthsAgo) {
                return [
                    'database' => $this->activeDb,
                    'collection' => $this->activeCollection,
                    'type' => 'active',
                ];
            }
            throw new RuntimeException('⛔ The selected range crosses into an archived period.');
        }

        // أقدم → أرشيف
        $collection = $this->quarterCollection($to);
        return [
            'database' => $this->archiveDb,
            'collection' => $collection,
            'type' => 'archive',
        ];
    }
}
