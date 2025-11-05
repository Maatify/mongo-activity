<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 10:13
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Contract;

/**
 * Interface ActivityTypeInterface
 *
 * Defines the contract for enumerating all valid activity types
 * that can be recorded in MongoDB activity logs.
 *
 * Implementing Enums should represent activity categories such as:
 * view, create, update, delete, system, etc.
 */
interface ActivityLogTypeInterface
{
    /**
     * Returns a list of available activity type values.
     *
     * @return string[]
     */
    public static function list(): array;
}
