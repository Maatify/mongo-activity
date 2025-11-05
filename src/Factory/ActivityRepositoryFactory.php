<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 10:40
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Factory;

use Maatify\MongoActivity\Repository\ActivityRepository;
use MongoDB\Client;

/**
 * Class ActivityRepositoryFactory
 *
 * Factory for creating {@see ActivityRepository} instances
 * based on environment configuration (.env).
 *
 * Reads MongoDB connection parameters from:
 * - MONGO_URI
 * - MONGO_DB_ACTIVITY
 */
final class ActivityRepositoryFactory
{
    /**
     * Create a new ActivityRepository instance using environment variables.
     *
     * @return ActivityRepository
     */
    public static function fromEnv(): ActivityRepository
    {
        $client = new Client($_ENV['MONGO_URI']);
        return new ActivityRepository($client);
    }
}
