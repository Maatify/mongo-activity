<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 12:54
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Contract;

/**
 * Interface ActionLogInterface
 *
 * Defines a contract for enums or classes that represent a set of
 * predefined action identifiers to be used in activity logging.
 *
 * Any implementing enum must provide a static `list()` method
 * that returns all possible action values as strings.
 */
interface ActionLogInterface
{
    /**
     * Returns all available action identifiers as a list of strings.
     *
     * @return string[] Array of action names.
     */
    public static function list(): array;
}
