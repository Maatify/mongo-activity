<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 11:34
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Manager;

use DateTimeImmutable;
use MongoDB\Client;
use Maatify\MongoActivity\Enum\QuarterEnum;
use MongoDB\Collection;

/**
 * Class ActivityArchiveManager
 *
 * Handles all archive-related logic for quarterly MongoDB storage.
 * Responsible for:
 *  - Defining active and archived collections.
 *  - Determining the quarter from a given date.
 *  - Returning metadata for current and historical archive periods.
 */
final class ActivityArchiveManager
{
    public function __construct(private readonly Client $client) {}

    /**
     * Get metadata for the current (active) period.
     *
     * @return array{
     *     db: string,
     *     collection: string,
     *     start: string,
     *     end: string
     * }
     *     Array containing the active database, collection name,
     *     and the start/end date range for the current period.
     */
    public function currentPeriod(): array
    {
        $now = new DateTimeImmutable('now');
        $start = $now->modify('-6 months')->format('Y-m-d');
        $end = $now->format('Y-m-d');

        return [
            'db' => $_ENV['MONGO_DB_ACTIVITY'] ?? 'maatify_activity',
            'collection' => 'user_activities',
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * Generate a collection name based on a given date.
     *
     * @param DateTimeImmutable $date Reference date.
     *
     * @return string Collection name in the format `user_activities_{year}_{quarter}`.
     *
     * Example:
     * ```php
     * ActivityArchiveManager::quarterCollection(new DateTimeImmutable('2025-02-10'));
     * // returns "user_activities_2025_Q1"
     * ```
     */
    public static function quarterCollection(DateTimeImmutable $date): string
    {
        $year = $date->format('Y');
        $quarter = QuarterEnum::fromMonth((int)$date->format('n'))->value;
        return sprintf('user_activities_%s_%s', $year, $quarter);
    }

    /**
     * Return all quarterly archive periods (existing or expected).
     *
     * @param int $yearsBack Number of past years to include (default: 2).
     *
     * @return array<int, array{
     *     db: string,
     *     collection: string,
     *     label: string
     * }>
     *     List of archive metadata grouped by year and quarter.
     */
    public function listArchivePeriods(int $yearsBack = 2): array
    {
        $list = [];
        $now = (int)date('Y');

        for ($year = $now - $yearsBack; $year <= $now; $year++) {
            foreach (QuarterEnum::cases() as $quarter) {
                $list[] = [
                    'db' => $_ENV['MONGO_DB_ACTIVITY_ARCHIVE'] ?? 'maatify_activity_archive',
                    'collection' => sprintf('user_activities_%d_%s', $year, $quarter->value),
                    'label' => sprintf('%s %d', $quarter->value, $year),
                ];
            }
        }

        return $list;
    }

    /**
     * Retrieve a MongoDB Collection instance for a specific archive.
     *
     * @param string $db         Database name.
     * @param string $collection Collection name.
     *
     * @return Collection The MongoDB collection instance.
     */
    public function getArchiveCollection(string $db, string $collection): Collection
    {
        return $this->client->selectCollection($db, $collection);
    }
}
