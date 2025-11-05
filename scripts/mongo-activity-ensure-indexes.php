#!/usr/bin/env php
<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 10:52
 * Project: maatify-mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use Maatify\MongoActivity\Repository\ActivityRepository;
use MongoDB\Client;
use Dotenv\Dotenv;

/**
 * @return string
 */
function getDirname(): string
{
    $vendorPos = strpos(__DIR__, '/vendor/');

    if ($vendorPos !== false) {
        // 🧩 Library used inside a project
        $baseDir = dirname(__DIR__, 3);
    } else {
        // 🧪 Standalone development mode
        $baseDir = dirname(__DIR__);
    }

    // Autoload dependencies (project or library scope)
    $autoloadPath = file_exists($baseDir . '/vendor/autoload.php')
        ? $baseDir . '/vendor/autoload.php'
        : __DIR__ . '/../vendor/autoload.php';

    require $autoloadPath;

    return $baseDir;
}

$baseDir = getDirname();

// Load environment variables (if .env exists)
if (file_exists($baseDir . '/.env')) {
    Dotenv::createImmutable($baseDir)->load();
}

// 🧩 Connect to MongoDB
$mongoUri = $_ENV['MONGO_URI'] ?? 'mongodb://127.0.0.1:27017';
$mongoDb  = $_ENV['MONGO_DB_ACTIVITY'] ?? 'maatify_activity';
$mongoCol = $_ENV['MONGO_COLLECTION_ACTIVITY'] ?? 'user_activities';

// 🧱 Ensure indexes
$client = new Client($mongoUri);
$repo   = new ActivityRepository($client, $mongoDb, $mongoCol);
$repo->ensureIndexes();
$repo->ensureSearchIndexes();



echo "✅ MongoDB activity indexes ensured successfully for [{$mongoDb}.{$mongoCol}]\n";

$count = iterator_count($repo->getCollection()->listIndexes());
echo "📊 Total indexes now: {$count}\n";
