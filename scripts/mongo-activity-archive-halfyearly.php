#!/usr/bin/env php
<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 11:38
 * Project: maatify-mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use Maatify\MongoActivity\Manager\ActivityArchiveManager;
use Maatify\MongoActivity\Repository\ArchiveRepository;
use Maatify\MongoActivity\Repository\ActivityRepository;
use Maatify\MongoActivity\Utils\ActivityIndexBuilder;
use MongoDB\Client;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$baseDir = dirname(__DIR__);

// 🔧 تحميل الـ ENV
if (file_exists($baseDir . '/.env')) {
    Dotenv::createImmutable($baseDir)->load();
}

// ⚙️ إعداد الاتصال بـ Mongo
$mongoUri = $_ENV['MONGO_URI'] ?? 'mongodb://127.0.0.1:27017';
$activeDb = $_ENV['MONGO_DB_ACTIVITY'] ?? 'maatify_activity';
$archiveDb = $_ENV['MONGO_DB_ACTIVITY_ARCHIVE'] ?? 'maatify_activity_archive';

$client = new Client($mongoUri);
$manager = new ActivityArchiveManager($client);
$activeRepo = new ActivityRepository($client);

echo "🕒 Starting half-yearly archive job...\n";

// 🧮 تحديد التاريخ الحد الفاصل
$cutoff = (new DateTimeImmutable('now'))->modify('-6 months');
echo "➡️ Archiving records older than {$cutoff->format('Y-m-d')}...\n";

// 🧲 جلب السجلات الأقدم من 6 شهور
$records = $activeRepo->findOlderThan($cutoff);

if (empty($records)) {
    echo "✅ No records found for archival.\n";
    exit(0);
}

echo "📦 Found " . count($records) . " records to archive.\n";

// 🗃️ تجميع السجلات حسب الربع
$archives = [];
foreach ($records as $record) {
    // ⏱️ نحول created_at لـ DateTime
    if (!isset($record['created_at'])) {
        continue;
    }

    $createdAt = $record['created_at'] instanceof MongoDB\BSON\UTCDateTime
        ? $record['created_at']->toDateTime()
        : new DateTimeImmutable($record['created_at']);

    // 🧩 نحدد اسم الـ collection حسب التاريخ
    $collectionName = ActivityArchiveManager::quarterCollection($createdAt);
    $archives[$collectionName][] = $record;


}

// 🪣 نقل كل مجموعة للأرشيف المناسب
$totalArchived = 0;
foreach ($archives as $collectionName => $recordsSet) {
    $collection = $client->selectCollection($archiveDb, $collectionName);
    $archiveRepo = new ArchiveRepository($collection);

    $archiveRepo->insertMany($recordsSet);
    $totalArchived += count($recordsSet);

    echo "✅ Archived " . count($recordsSet) . " records to {$collectionName}\n";

    // ⚙️ تأكد إن الاندكسات لسه مش متعملتش
    $existingIndexes = iterator_to_array($collection->listIndexes());
    if (count($existingIndexes) <= 1) { // Mongo بيعمل دايمًا _id_ index
        ActivityIndexBuilder::ensureAll($collection);
        echo "🔗 Indexes created for new collection: {$collectionName}\n";
    } else {
        echo "⏩ Skipped index creation for existing collection: {$collectionName}\n";
    }
}

// 🧹 حذفهم من الـ active collection
$deletedCount = $activeRepo->deleteOlderThan($cutoff);
echo "🗑️  Deleted {$deletedCount} records from active logs.\n";

// 📘 النتيجة النهائية
echo "🎯 Archival completed successfully.\n";
echo "📊 Total moved: {$totalArchived}\n";
echo "📁 Archive DB: {$archiveDb}\n";
echo "----------------------------------------\n";
