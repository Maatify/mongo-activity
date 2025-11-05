<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 11:35
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Repository;

use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;

/**
 * Class ArchiveRepository
 *
 * Handles interactions with archived activity collections.
 * Provides methods for bulk insertion and range-based retrieval
 * of archived user activity records.
 */
final class ArchiveRepository
{
    public function __construct(private readonly Collection $collection) {}

    /**
     * Insert multiple activity records into the archive collection.
     *
     * @param array<int, array<string, mixed>> $records
     *        List of activity documents to insert.
     *
     * @return void
     */
    public function insertMany(array $records): void
    {
        if (!empty($records)) {
            $this->collection->insertMany($records);
        }
    }

    /**
     * Retrieve all archived activities between two timestamps.
     *
     * @param string $from Start date (ISO 8601 string or any strtotime-compatible format).
     * @param string $to   End date (ISO 8601 string or any strtotime-compatible format).
     *
     * @return array<int, array<string, mixed>>
     *         Array of matched activity records sorted by `created_at` (descending).
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
            ->find($filter, ['sort' => ['created_at' => -1]])
            ->toArray();
    }

}
