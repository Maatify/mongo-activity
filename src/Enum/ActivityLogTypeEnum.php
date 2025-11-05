<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 09:47
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Enum;

use Maatify\MongoActivity\Contract\ActivityLogTypeInterface;

/**
 * Enum ActivityTypeEnum
 *
 * Represents the different categories of activities
 * that can be logged into MongoDB using the `maatify/mongo-activity` system.
 *
 * Each value corresponds to a specific type of event or system action.
 *
 * ### Usage Example:
 * ```php
 * use Maatify\MongoActivity\Enum\ActivityTypeEnum;
 *
 * $activityType = ActivityTypeEnum::CREATE;
 * $logger->recordActivity($activityType, 'User created a new post.');
 * ```
 *
 * ### Enum Values:
 * - `VIEW`   → When a resource is viewed (read-only action)
 * - `CREATE` → When a new resource is created
 * - `UPDATE` → When an existing resource is modified
 * - `DELETE` → When a resource is removed
 * - `SYSTEM` → For internal or automatic system events
 *
 * @package Maatify\MongoActivity\Enum
 */
enum ActivityLogTypeEnum: string implements ActivityLogTypeInterface
{
    /** 📄 When a user or process views a record or page */
    case VIEW = 'view';

    /** ➕ When a new record is created */
    case CREATE = 'create';

    /** ✏️ When an existing record is updated */
    case UPDATE = 'update';

    /** ❌ When a record is deleted */
    case DELETE = 'delete';

    /** ⚙️ System-triggered or automated event (e.g. cron, maintenance) */
    case SYSTEM = 'system';

    /**
     * Returns a list of all Activity as string values.
     *
     * @return string[]
     */
    public static function list(): array
    {
        return array_column(self::cases(), 'value');
    }
}
