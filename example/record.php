<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 12:27
 * Project: maatify-mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use Maatify\MongoActivity\Enum\ActionLogEnum;
use Maatify\MongoActivity\Enum\AppLogModulesEnum;
use Maatify\MongoActivity\Enum\UserLogRoleEnum;
use MongoDB\Client;
use Maatify\MongoActivity\Manager\ActivityManager;
use Maatify\MongoActivity\Repository\ActivityRepository;
use Maatify\MongoActivity\DTO\ActivityRecordDTO;
use Maatify\MongoActivity\Enum\ActivityLogTypeEnum;

$client = new Client($_ENV['MONGO_URI']);
$activityRepo = new ActivityRepository(
    client: $client,
    database: $_ENV['MONGO_DB_ACTIVITY'] ?? 'maatify_activity',
    collection: $_ENV['MONGO_COLLECTION_ACTIVITY'] ?? 'user_activities',
    enabled: filter_var($_ENV['MONGO_ACTIVITY_ENABLED'] ?? true, FILTER_VALIDATE_BOOL)
);
$manager = new ActivityManager($activityRepo);

// عند مشاهدة منتج
$manager->record(new ActivityRecordDTO(
    userId: 5001,
    role: UserLogRoleEnum::CUSTOMER,
    type: ActivityLogTypeEnum::VIEW,
    module: AppLogModulesEnum::PRODUCT,
    action: ActionLogEnum::VIEW_PRODUCT->value,
    description: 'Viewed product #302',
    refId: 302,
    ip: $_SERVER['REMOTE_ADDR'],
    userAgent: $_SERVER['HTTP_USER_AGENT']
));

// عند تعديل بيانات
$manager->record(new ActivityRecordDTO(
    userId: 5001,
    role: UserLogRoleEnum::CUSTOMER,
    type: ActivityLogTypeEnum::UPDATE,
    module: AppLogModulesEnum::AUTH,
    action: ActionLogEnum::UPDATE_INFO->value,
    description: 'Changed email to new@domain.com'
));

/*
// عند مشاهدة منتج
$manager->record(new ActivityRecordDTO(
    userId: 5001,
    role: 'customer',
    type: ActivityTypeEnum::VIEW,
    module: 'product',
    action: 'view_product',
    description: 'Viewed product #302',
    refId: 302,
    ip: $_SERVER['REMOTE_ADDR'],
    userAgent: $_SERVER['HTTP_USER_AGENT']
));

// عند تعديل بيانات
$manager->record(new ActivityRecordDTO(
    userId: 5001,
    role: 'customer',
    type: ActivityTypeEnum::UPDATE,
    module: 'profile',
    action: 'update_email',
    description: 'Changed email to new@domain.com'
));
*/
