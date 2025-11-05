<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 12:24
 * Project: maatify-mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use MongoDB\Client;
use Maatify\MongoActivity\Repository\ActivityRepository;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$client = new Client($_ENV['MONGO_URI']);

$activityRepo = new ActivityRepository(
    client: $client,
    database: $_ENV['MONGO_DB_ACTIVITY'] ?? 'maatify_activity',
    collection: $_ENV['MONGO_COLLECTION_ACTIVITY'] ?? 'user_activities',
    enabled: filter_var($_ENV['MONGO_ACTIVITY_ENABLED'] ?? true, FILTER_VALIDATE_BOOL)
);