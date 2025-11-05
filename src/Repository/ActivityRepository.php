<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 09:52
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Repository;

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
use MongoDB\Collection;

/**
 * Class ActivityRepository
 *
 * Handles all MongoDB operations related to user activity logs.
 * Provides methods for inserting, retrieving, and searching activity records.
 *
 * ⚙️ Collection: `user_activities`
 */
final class ActivityRepository
{
    private Collection $collection;
    private bool $enabled;

    /**
     * ActivityRepository constructor.
     *
     * @param Client $client     MongoDB client instance connected to the desired server.
     * @param string $database   MongoDB database name (default: maatify_activity).
     * @param string $collection Collection name (default: user_activities).
     * @param   bool  $enabled  Whether the activity system is active (default: true).
     */
    public function __construct(
        Client $client,
        string $database = 'maatify_activity',
        string $collection = 'user_activities',
        bool $enabled = true
    ) {
        $this->collection = $client->selectCollection($database, $collection);
        $this->enabled = $enabled;
        // ⚙️ Auto-create essential indexes (if not already present)
//        $this->ensureIndexes();
    }

    /**
     * 🧩 Create essential indexes for faster queries.
     *
     * Ensures optimal performance for common query patterns such as:
     * - Searching by user_id
     * - Sorting/filtering by created_at
     * - Filtering by module or role
     */
    public function ensureIndexes(): void
    {
        $existingIndexes = iterator_to_array($this->collection->listIndexes());
        $existingNames = array_column($existingIndexes, 'name');

        $indexes = [
            ['key' => ['user_id' => 1], 'name' => 'idx_user_id'],
            ['key' => ['created_at' => -1], 'name' => 'idx_created_at'],
            ['key' => ['module' => 1], 'name' => 'idx_module'],
            ['key' => ['role' => 1], 'name' => 'idx_role'],
            ['key' => ['type' => 1], 'name' => 'idx_type'],
        ];

        foreach ($indexes as $index) {
            if (!in_array($index['name'], $existingNames, true)) {
                $this->collection->createIndex($index['key'], ['name' => $index['name']]);
            }
        }
    }

    /**
     * 🧭 Ensure compound indexes for optimized search queries.
     *
     * These indexes are designed to speed up search() queries that filter
     * by user_id, module, type, and date range (created_at).
     */
    public function ensureSearchIndexes(): void
    {
        $existingIndexes = iterator_to_array($this->collection->listIndexes());
        $existingNames = array_column($existingIndexes, 'name');

        $compoundIndexes = [
            [
                'key' => [
                    'user_id' => 1,
                    'created_at' => -1
                ],
                'name' => 'idx_userid_createdat'
            ],
            [
                'key' => [
                    'module' => 1,
                    'created_at' => -1
                ],
                'name' => 'idx_module_createdat'
            ],
            [
                'key' => [
                    'type' => 1,
                    'created_at' => -1
                ],
                'name' => 'idx_type_createdat'
            ],
            [
                'key' => [
                    'role' => 1,
                    'created_at' => -1
                ],
                'name' => 'idx_role_createdat'
            ],
            [
                'key' => [
                    'user_id' => 1,
                    'module' => 1,
                    'type' => 1,
                    'created_at' => -1
                ],
                'name' => 'idx_user_module_type_createdat'
            ],
        ];

        foreach ($compoundIndexes as $index) {
            if (!in_array($index['name'], $existingNames, true)) {
                $this->collection->createIndex($index['key'], ['name' => $index['name']]);
            }
        }
    }


    /**
     * ➕ Insert a new activity record.
     *
     * @param array $data Associative array representing the activity document.
     *
     * Example:
     * ```php
     * $repo->insert([
     *     'user_id' => 501,
     *     'role' => 'admin',
     *     'type' => 'update',
     *     'module' => 'product',
     *     'action' => 'edit_price',
     *     'created_at' => new UTCDateTime(),
     * ]);
     * ```
     */
    public function insert(array $data): void
    {
        if (! $this->enabled) {
            // ⏸️ Logging disabled — skip silently
            return;
        }

        $this->collection->insertOne($data);
    }

    /**
     * 👤 Fetch recent activities for a specific user.
     *
     * @param int $userId User ID to filter by.
     * @param int $limit  Maximum number of records to fetch (default: 20).
     *
     * @return array Array of recent user activities.
     */
    public function findByUser(int $userId, int $limit = 20): array
    {
        if (! $this->enabled) {
            // ⏸️ Logging disabled — skip silently
            return [];
        }

        return $this->collection->find(
            ['user_id' => $userId],
            ['limit' => $limit, 'sort' => ['created_at' => -1]]
        )->toArray();
    }

    /**
     * 🔍 Search activity records with advanced filtering and pagination.
     *
     * Allows searching the MongoDB `user_activities` collection using multiple optional filters
     * such as user ID, role, module, type, reference ID, keyword, and date range.
     * Supports pagination and sorting by creation date.
     *
     * Example:
     * ```php
     * $result = $repo->search(
     *     userId: 501,
     *     module: AppModulesEnum::PRODUCT,
     *     keyword: 'price',
     *     from: '2025-11-01T00:00:00',
     *     to: '2025-11-05T23:59:59',
     *     perPage: 10
     * );
     * ```
     *
     * @param int|null                                    $userId     Filter by user ID.
     * @param (UserLogRoleInterface&BackedEnum)|null      $role       Filter by user role.
     * @param int|null                                    $refId      Filter by reference ID (e.g., related entity).
     * @param (AppLogModuleInterface&BackedEnum)|null     $module     Filter by application module.
     * @param (ActivityLogTypeInterface&BackedEnum)|null  $type       Filter by activity type (e.g., create, update, view).
     * @param string|null                                 $keyword    Keyword to search in `description` or `action` (case-insensitive).
     * @param string|null                                 $from       Start date (ISO 8601), e.g. '2025-11-01T00:00:00'.
     * @param string|null                                 $to         End date (ISO 8601), e.g. '2025-11-05T23:59:59'.
     * @param int                                         $page       Page number for pagination (default: 1).
     * @param int                                         $perPage    Number of results per page (default: 20).
     * @param string                                      $sortOrder  Sort direction — 'asc' or 'desc' (default: 'desc').
     *
     * @return array{
     *     data: array,
     *     meta: array{
     *         page: int,
     *         per_page: int,
     *         total: int,
     *         total_pages: int
     *     }
     * }
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
        $filter = [];

        // 🎯 Apply filters if provided
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

        // 🕒 Date range filtering
        if ($from !== null || $to !== null) {
            $dateFilter = [];
            if ($from !== null) {
                $dateFilter['$gte'] = new UTCDateTime(strtotime($from) * 1000);
            }
            if ($to !== null) {
                $dateFilter['$lte'] = new UTCDateTime(strtotime($to) * 1000);
            }
            $filter['created_at'] = $dateFilter;
        }

        // 🔍 Keyword search (description + action)
        if (!empty($keyword)) {
            $filter['$or'] = [
                ['description' => ['$regex' => $keyword, '$options' => 'i']],
                ['action'      => ['$regex' => $keyword, '$options' => 'i']],
            ];
        }

        // 🔄 Sorting setup
        $sortDirection = strtolower($sortOrder) === 'asc' ? 1 : -1;

        // 📄 Pagination
        $skip = ($page - 1) * $perPage;

        // 🔍 Query execution
        $cursor = $this->collection->find(
            $filter,
            [
                'limit' => $perPage,
                'skip'  => $skip,
                'sort'  => ['created_at' => $sortDirection],
            ]
        );

        $data = $cursor->toArray();
        $total = $this->collection->countDocuments($filter);

        $paginationData = PaginationHelper::paginate(
            items: range(1, $total),
            page: $page,
            perPage: $perPage
        )['pagination'];

        $pagination = PaginationDTO::fromArray($paginationData);

        return new PaginationResultDTO(
            data: $data,
            pagination: $pagination
        );

    }

    public function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * Find all activity records created within a given date range.
     *
     * Used for archival operations — e.g., moving logs older than 3 or 6 months.
     *
     * @param string $from Date string (Y-m-d or ISO8601)
     * @param string $to   Date string (Y-m-d or ISO8601)
     * @return array
     */
    public function findByRange(string $from, string $to): array
    {
        $filter = [
            'created_at' => [
                '$gte' => new UTCDateTime(strtotime($from) * 1000),
                '$lte' => new UTCDateTime(strtotime($to) * 1000),
            ],
        ];

        return $this->collection
            ->find($filter, ['sort' => ['created_at' => 1]])
            ->toArray();
    }



    /**
     * Delete records in a given date range (after successful archive transfer).
     *
     * @param string $from
     * @param string $to
     * @return int Deleted count
     */
    public function deleteByRange(string $from, string $to): int
    {
        $filter = [
            'created_at' => [
                '$gte' => new UTCDateTime(strtotime($from) * 1000),
                '$lte' => new UTCDateTime(strtotime($to) * 1000),
            ],
        ];

        $result = $this->collection->deleteMany($filter);
        return $result->getDeletedCount();
    }

    /**
     * Find all records older than the given date.
     */
    public function findOlderThan(DateTimeImmutable $cutoff): array
    {
        $filter = ['created_at' => ['$lt' => new UTCDateTime($cutoff->getTimestamp() * 1000)]];
        return $this->collection->find($filter)->toArray();
    }

    /**
     * Delete all records older than the given date.
     */
    public function deleteOlderThan(DateTimeImmutable $cutoff): int
    {
        $filter = ['created_at' => ['$lt' => new UTCDateTime($cutoff->getTimestamp() * 1000)]];
        $result = $this->collection->deleteMany($filter);
        return $result->getDeletedCount();
    }
}
