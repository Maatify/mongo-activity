<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 10:12
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Enum;

use Maatify\MongoActivity\Contract\UserLogRoleInterface;

/**
 * Enum UserRoleEnum
 *
 * Defines all actor roles that can appear in system activities.
 * Useful for distinguishing between user, staff, automation,
 * and system-level actions in MongoDB logs.
 *
 * ### Example:
 * ```php
 * use Maatify\MongoActivity\Enum\UserRoleEnum;
 *
 * $role = UserRoleEnum::SUPPORT;
 * ```
 */
enum UserLogRoleEnum: string implements UserLogRoleInterface
{
    /** 👑 Super admin or full-control user */
    case ADMIN = 'admin';

    /** 👤 Registered end-user or customer */
    case CUSTOMER = 'customer';

    /** 🧑‍💼 Support or staff handling requests */
    case SUPPORT = 'support';

    /** 🧭 Manager, moderator, or department lead */
    case MANAGER = 'manager';

    /** 🤝 External business partner or vendor */
    case PARTNER = 'partner';

    /** ⚙️ System or backend internal process */
    case SYSTEM = 'system';

    /** 🤖 Automated process or AI-driven action */
    case AI = 'ai';

    /** 🔗 Third-party integration (API / webhook) */
    case INTEGRATION = 'integration';

    /** 🕒 Cron or scheduled background task */
    case CRON = 'cron';

    /** 👀 Anonymous or guest visitor (not logged in) */
    case GUEST = 'guest';

    /**
     * Returns a list of all role values as string values.
     *
     * @return string[]
     */
    public static function list(): array
    {
        return array_column(self::cases(), 'value');
    }
}
