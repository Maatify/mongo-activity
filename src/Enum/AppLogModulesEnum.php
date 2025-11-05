<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 10:02
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Enum;

use Maatify\MongoActivity\Contract\AppLogModuleInterface;

/**
 * Enum AppModulesEnum
 *
 * Defines the default application modules for MongoDB activity tracking.
 * Each value represents a logical domain of user/system activity.
 *
 * ### Example:
 *      use Maatify\MongoActivity\Enum\AppModulesEnum;
 *
 *      $module = AppModulesEnum::ORDER;
 *      $activityManager->record(new ActivityRecordDTO(
 *          userId: 5001,
 *          role: 'customer',
 *          type: ActivityTypeEnum::CREATE,
 *          module: $module->value,
 *          action: 'place_order'
 *      ));
 */
enum AppLogModulesEnum: string implements AppLogModuleInterface
{
    /**
     * 🛍️ Product management module
     * Used for actions like creating, updating, or viewing products.
     */
    case PRODUCT = 'product';

    /**
     * 🧾 Order module
     * Tracks all actions related to order creation, payment, delivery, or cancellation.
     */
    case ORDER = 'order';

    /**
     * 🔐 Authentication and authorization module
     * Includes login, logout, registration, password changes, and 2FA.
     */
    case AUTH = 'auth';

    /**
     * ⚙️ System and user settings module
     * Covers preference updates, configuration changes, and admin adjustments.
     */
    case SETTINGS = 'settings';

    /**
     * 💰 Wallet and financial transactions module
     * Handles actions related to wallet top-up, balance checking, or withdrawals.
     */
    case WALLET = 'wallet';

    /**
     * Returns a list of all modules as string values.
     *
     * @return string[]
     */
    public static function list(): array
    {
        return array_column(self::cases(), 'value');
    }
}
