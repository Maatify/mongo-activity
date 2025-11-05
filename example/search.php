<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 13:26
 * Project: maatify-mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use Maatify\MongoActivity\Enum\AppLogModulesEnum;
use Maatify\MongoActivity\Resolver\ActivityPeriodResolver;
use MongoDB\Client;

$client = new Client($_ENV['MONGO_URI']);

$resolver = new ActivityPeriodResolver(
    client: $client,
    activeDb: 'maatify_activity',
    archiveDb: 'maatify_activity_archive',
);

$result = $resolver->search(
    userId: 501,
    module: AppLogModulesEnum::PRODUCT,
    keyword: 'discount'
);

return json_encode($result->toArray());