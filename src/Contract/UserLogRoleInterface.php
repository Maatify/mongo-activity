<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 10:10
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Contract;

/**
 * Interface RoleInterface
 *
 * Defines a contract for enumerating user roles available in the system.
 * Any role enum (e.g., UserRoleEnum, AdminRoleEnum, ApiRoleEnum)
 * should implement this interface to ensure consistent structure.
 */
interface UserLogRoleInterface
{
    /**
     * Returns the list of role values.
     *
     * @return string[]
     */
    public static function list(): array;
}
