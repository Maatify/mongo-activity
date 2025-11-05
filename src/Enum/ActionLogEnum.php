<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 12:55
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Enum;

use Maatify\MongoActivity\Contract\ActionLogInterface;

/**
 * Enum ActionLogEnum
 *
 * Represents predefined system actions that can be recorded in activity logs.
 * Each case identifies a specific user or system action such as product creation,
 * update, or deletion.
 */
enum ActionLogEnum: string implements ActionLogInterface
{
    /** 👁️ Viewing an existing product */
    case VIEW_PRODUCT = 'view_product';

    /** ➕ Creating a new product */
    case CREATE_PRODUCT = 'create_product';

    /** ✏️ Updating an existing product */
    case UPDATE_PRODUCT = 'update_product';

    /** ❌ Deleting a product */
    case DELETE_PRODUCT = 'delete_product';

    /** ✏️ Updating general information (e.g., profile, settings) */
    case UPDATE_INFO = 'update_info';

    /** ➕ Creating a new information entry */
    case CREATE_INFO = 'create_info';

    /** ❌ Deleting an information entry */
    case DELETE_INFO = 'delete_info';

    /**
     * Returns all available action values as a list of strings.
     *
     * @return string[] Array of action identifiers.
     */
    public static function list(): array
    {
        return array_column(self::cases(), 'value');
    }
}
