<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 10:01
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Contract;

/**
 * Interface ModuleInterface
 *
 * Defines a contract for module enumeration used in MongoDB activity logging.
 * Each project (e.g., EP4N, Education Platform, CMS, etc.)
 * should implement this interface to provide its own list of logical modules.
 *
 * ### Example:
 * ```php
 * final class AppModules implements ModuleInterface
 * {
 *     public static function list(): array
 *     {
 *         return [
 *             'auth' => 'Authentication & Login',
 *             'orders' => 'Customer Orders',
 *             'wallet' => 'Wallet Transactions',
 *             'settings' => 'System Settings',
 *         ];
 *     }
 * }
 * ```
 *
 * This allows your ActivityManager to categorize actions
 * by project-specific modules consistently.
 */
interface AppLogModuleInterface
{
    /**
     * Returns a list of available module identifiers.
     *
     * @return array<string,string> Key-value pairs of module codes and human-readable names.
     */
    public static function list(): array;
}
