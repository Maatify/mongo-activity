<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 11:59
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Utils;

use MongoDB\Collection;

/**
 * Class ActivityIndexBuilder
 *
 * Ensures that all required indexes exist on the MongoDB activity collection.
 * It automatically creates single-field and compound indexes that improve
 * query performance for common filters such as user, role, module, type,
 * and date ranges.
 */
final class ActivityIndexBuilder
{
    /**
     * Ensure all essential indexes exist for the given activity collection.
     *
     * Creates the following indexes if they are missing:
     *  - Single-field indexes: user_id, module, role, type, created_at
     *  - Compound indexes:
     *      • user_id + created_at
     *      • module + created_at
     *      • type + created_at
     *      • role + created_at
     *      • user_id + module + type + created_at
     *
     * @param Collection $collection The MongoDB collection to check and update.
     *
     * @return void
     */
    public static function ensureAll(Collection $collection): void
    {
        $indexes = [
            ['key' => ['user_id' => 1], 'name' => 'idx_user_id'],
            ['key' => ['module' => 1], 'name' => 'idx_module'],
            ['key' => ['role' => 1], 'name' => 'idx_role'],
            ['key' => ['type' => 1], 'name' => 'idx_type'],
            ['key' => ['created_at' => -1], 'name' => 'idx_created_at'],

            // 🔍 Compound indexes for optimized searches
            ['key' => ['user_id' => 1, 'created_at' => -1], 'name' => 'idx_userid_createdat'],
            ['key' => ['module' => 1, 'created_at' => -1], 'name' => 'idx_module_createdat'],
            ['key' => ['type' => 1, 'created_at' => -1], 'name' => 'idx_type_createdat'],
            ['key' => ['role' => 1, 'created_at' => -1], 'name' => 'idx_role_createdat'],
            ['key' => ['user_id' => 1, 'module' => 1, 'type' => 1, 'created_at' => -1], 'name' => 'idx_user_module_type_createdat'],
        ];

        $existingIndexes = iterator_to_array($collection->listIndexes());
        $existingNames = array_column($existingIndexes, 'name');

        foreach ($indexes as $index) {
            if (!in_array($index['name'], $existingNames, true)) {
                $collection->createIndex($index['key'], ['name' => $index['name']]);
            }
        }
    }
}
