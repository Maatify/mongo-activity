![**Maatify.dev**](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)
---
[![Current version](https://img.shields.io/packagist/v/maatify/mongo-activity)](https://packagist.org/packages/maatify/mongo-activity)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/mongo-activity)](https://packagist.org/packages/maatify/mongo-activity)
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/mongo-activity)](https://packagist.org/packages/maatify/mongo-activity/stats)
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/mongo-activity)](https://packagist.org/packages/maatify/mongo-activity/stats)
[![License](https://img.shields.io/github/license/maatify/mongo-activity)](https://github.com/maatify/mongo-activity/blob/main/LICENSE)

# 📊 maatify/mongo-activity

**Advanced MongoDB-based user activity logging system**
Designed for tracking, querying, and archiving user/admin actions in a performant, structured, and easily searchable format.

---

## 🚀 Features

* ✅ **Structured activity tracking** — supports CRUD + view actions
* ✅ **Enum-based configuration** — roles, modules, and types
* ✅ **Optimized indexes** for performance
* ✅ **Advanced filtering** (user, role, module, type, keyword, date range)
* ✅ **Pagination-ready search results**
* ✅ **Quarter-based archiving system** (auto CRON support)
* ✅ **Environment-independent** — use via DI container
* ✅ **Dual database mode** (active + archive)

---

## 📁 Project Structure

```
maatify-mongo-activity/
├── src/
│   ├── Contract/
│   │   ├── AppModuleInterface.php
│   │   ├── ActivityTypeInterface.php
│   │   └── UserRoleInterface.php
│   ├── Enum/
│   │   ├── AppModulesEnum.php
│   │   ├── ActivityTypeEnum.php
│   │   └── UserRoleEnum.php
│   ├── DTO/
│   │   └── ActivityRecordDTO.php
│   ├── Repository/
│   │   ├── ActivityRepository.php
│   │   ├── ArchiveRepository.php
│   │   └── PeriodResolverRepository.php
│   ├── Manager/
│   │   └── ActivityArchiveManager.php
│   └── Helpers/
│       └── ActivityPeriodResolver.php
├── scripts/
│   ├── mongo-activity-ensure-indexes.php
│   └── mongo-activity-archive.php
├── .env.example
└── composer.json
```

---

## ⚙️ Installation

### 🟢 Public (via Packagist)

```bash
composer require maatify/mongo-activity
```

---

### 🔐 Private Repository (VCS)

If the library is private and hosted on GitHub, GitLab, or Bitbucket:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:maatify/mongo-activity.git"
    }
  ],
  "require": {
    "maatify/mongo-activity": "dev-main"
  }
}
```

Then install:

```bash
composer update maatify/mongo-activity
```

> ⚠️ Make sure your user has access to the private repository via SSH or a valid Personal Access Token.

---

### 🧱 Local Development (Path Repository)

If you are developing both the project and the library locally:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../maatify/mongo-activity",
      "options": { "symlink": true }
    }
  ],
  "require": {
    "maatify/mongo-activity": "dev-main"
  }
}
```

Install with:

```bash
composer require maatify/mongo-activity:dev-main
```

> ✅ Any change you make inside the library will instantly reflect in your project (no reinstall required).

---

### 🔑 Using a GitHub Access Token (HTTPS)

If you prefer HTTPS authentication instead of SSH:

```bash
composer config --global github-oauth.github.com ghp_yourAccessTokenHere
```

Then reference your repository as:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/maatify/mongo-activity.git"
    }
  ],
  "require": {
    "maatify/mongo-activity": "dev-main"
  }
}
```

---


## 🧩 Environment Example (`.env.example`)

```env
# Mongo Connection
MONGO_URI=mongodb://127.0.0.1:27017

# Databases
MONGO_DB_ACTIVITY=maatify_activity
MONGO_DB_ACTIVITY_ARCHIVE=maatify_activity_archive

# Collections
MONGO_COLLECTION_ACTIVITY=user_activities

# Feature toggle
MONGO_ACTIVITY_ENABLED=true
```

---

## 🧠 Basic Usage Example

```php
use MongoDB\Client;
use Maatify\MongoActivity\Repository\ActivityRepository;
use Maatify\MongoActivity\DTO\ActivityRecordDTO;
use Maatify\MongoActivity\Enum\AppModulesEnum;
use Maatify\MongoActivity\Enum\UserRoleEnum;
use Maatify\MongoActivity\Enum\ActivityTypeEnum;

$client = new Client($_ENV['MONGO_URI']);
$repo   = new ActivityRepository($client);

$repo->insert([
    'user_id'     => 501,
    'role'        => UserRoleEnum::ADMIN->value,
    'type'        => ActivityTypeEnum::UPDATE->value,
    'module'      => AppModulesEnum::PRODUCT->value,
    'action'      => 'edit_price',
    'description' => 'Updated product #312',
    'ref_id'      => 312,
    'created_at'  => new MongoDB\BSON\UTCDateTime(),
]);
```

---

## 🔍 Searching with Filters

```php
$result = $repo->search(
    userId: 501,
    module: AppModulesEnum::PRODUCT,
    keyword: 'price',
    from: '2025-11-01T00:00:00',
    to: '2025-11-05T23:59:59',
    perPage: 10
);
```

---

## 🗃️ Archiving Old Records

To move logs older than **6 months** to quarterly archive collections:

```bash
php scripts/mongo-activity-archive.php
```

It automatically moves data to collections such as:

```
user_activities_archive_2025_0103
user_activities_archive_2025_0406
user_activities_archive_2025_0709
user_activities_archive_2025_1012
```

---

## 🧱 Ensuring Indexes

To (re)create indexes for faster queries:

```bash
php scripts/mongo-activity-ensure-indexes.php
```

This ensures indexes for:

* `user_id`
* `created_at`
* `module`
* `role`
* `type`


---

## 🧩 Basic Usage Example

```php
use MongoDB\Client;
use Maatify\MongoActivity\Repository\ActivityRepository;
use Maatify\MongoActivity\Enum\AppModulesEnum;
use Maatify\MongoActivity\Enum\UserRoleEnum;
use Maatify\MongoActivity\Enum\ActivityTypeEnum;

$client = new Client($_ENV['MONGO_URI']);
$repo   = new ActivityRepository($client);

$repo->insert([
    'user_id'     => 501,
    'role'        => UserRoleEnum::ADMIN->value,
    'type'        => ActivityTypeEnum::UPDATE->value,
    'module'      => AppModulesEnum::PRODUCT->value,
    'action'      => 'edit_price',
    'description' => 'Updated product #312',
    'ref_id'      => 312,
    'created_at'  => new MongoDB\BSON\UTCDateTime(),
]);
```

---

## 🔍 Searching with Filters

```php
$result = $repo->search(
    userId: 501,
    module: AppModulesEnum::PRODUCT,
    keyword: 'price',
    from: '2025-11-01T00:00:00',
    to:   '2025-11-05T23:59:59',
    perPage: 10
);
```

---

## 🗃️ Archiving Old Records

Move logs older than 6 months to quarterly archive collections:

```bash
php scripts/mongo-activity-archive.php
```

Creates collections such as:

```
user_activities_archive_2025_0103
user_activities_archive_2025_0406
user_activities_archive_2025_0709
user_activities_archive_2025_1012
```

---

## 🧱 Ensuring Indexes

To (re)create performance indexes:

```bash
php scripts/mongo-activity-ensure-indexes.php
```

Creates indexes on:

* `user_id`
* `created_at`
* `module`
* `role`
* `type`

---

## 🔌 Integration with Slim Framework / DI Container

### Register the Mongo Client Service

```php
use MongoDB\Client;
use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    Client::class => fn() => new Client($_ENV['MONGO_URI']),
]);
$container = $containerBuilder->build();
```

### Register the Activity Repository

```php
use Maatify\MongoActivity\Repository\ActivityRepository;

$containerBuilder->addDefinitions([
    ActivityRepository::class => fn($c) =>
        new ActivityRepository(
            $c->get(Client::class),
            $_ENV['MONGO_DB_ACTIVITY'],
            $_ENV['MONGO_COLLECTION_ACTIVITY']
        ),
]);
```

### Use inside a Route

```php
$app->post('/log', function ($request, $response) use ($container) {
    $repo = $container->get(ActivityRepository::class);
    $repo->insert([
        'user_id' => 123,
        'role' => 'admin',
        'type' => 'update',
        'module' => 'settings',
        'action' => 'change_password',
        'description' => 'Admin updated user password',
        'created_at' => new MongoDB\BSON\UTCDateTime(),
    ]);
    $response->getBody()->write('Activity logged.');
    return $response;
});
```

---

## 🧩 CRON Tasks Summary

| Script                                      | Purpose                            | Schedule              |
|---------------------------------------------|------------------------------------|-----------------------|
| `scripts/mongo-activity-archive.php`        | Archive logs older than 6 months   | Every 6 months        |
| `scripts/mongo-activity-ensure-indexes.php` | Verify indexes for all collections | Once after deployment |



---

## ⚙️ Requirements

| Dependency            | Minimum Version | Notes                         |
|-----------------------|-----------------|-------------------------------|
| PHP                   | **8.4**         | Native enums & readonly props |
| `mongodb/mongodb`     | **2+**          | Official MongoDB driver       |
| `vlucas/phpdotenv`    | **5.6+**        | For `.env` loading (optional) |

---

## 🧰 Dependencies

* PHP ≥ 8.4
* `mongodb/mongodb` ≥ 2
* `vlucas/phpdotenv` ≥ 5.6

---
## 🪪 License

**[MIT license](LICENSE)** © [Maatify.dev](https://www.maatify.dev)

You’re free to use, modify, and distribute this library with attribution.

---
## 🧑‍💻 Maintainer

**Maatify.dev**
[https://www.Maatify.dev](https://www.Maatify.dev)

---
