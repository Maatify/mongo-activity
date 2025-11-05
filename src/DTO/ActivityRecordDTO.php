<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 09:49
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\DTO;

use BackedEnum;
use Maatify\MongoActivity\Contract\ActionLogInterface;
use Maatify\MongoActivity\Contract\ActivityLogTypeInterface;
use Maatify\MongoActivity\Contract\AppLogModuleInterface;
use Maatify\MongoActivity\Contract\UserLogRoleInterface;
use Maatify\MongoActivity\Enum\ActivityLogTypeEnum;
use Maatify\MongoActivity\Enum\UserLogRoleEnum;

/**
 * Class ActivityRecordDTO
 *
 * Represents a standardized structure for a single activity record
 * to be stored in MongoDB through the `maatify:mongo-activity` system.
 *
 * Each record captures who performed an action, what they did, and
 * contextual details like IP, device, and related entity reference.
 *
 * ### Example Usage:
 * ```php
 * use Maatify\MongoActivity\DTO\ActivityRecordDTO;
 * use Maatify\MongoActivity\Enum\ActivityTypeEnum;
 *
 * $record = new ActivityRecordDTO(
 *     userId: 42,
 *     role: 'admin',
 *     type: ActivityTypeEnum::UPDATE,
 *     module: 'Products',
 *     action: 'Edit product details',
 *     description: 'Changed price and stock availability',
 *     refId: 1042,
 *     ip: '192.168.1.10',
 *     userAgent: $_SERVER['HTTP_USER_AGENT'] ?? 'CLI'
 * );
 * ```
 *
 * @package Maatify\MongoActivity\DTO
 */
final class ActivityRecordDTO
{
    /**
     * @param int                                  $userId       User unique ID
     * @param UserLogRoleInterface&BackedEnum      $role         Role of the actor (admin, customer, etc.)
     * @param ActivityLogTypeInterface&BackedEnum  $type         Type of activity performed
     * @param AppLogModuleInterface&BackedEnum     $module       Module or system section name
     * @param ActionLogInterface&BackedEnum        $action       Specific action performed
     * @param string|null                          $description  Optional detailed description
     * @param int|null                             $refId        Optional reference entity ID (e.g., order_id, product_id)
     * @param string|null                          $ip           Request IP address
     * @param string|null                          $userAgent    Request user agent (browser/device)
     */
    public function __construct(
        public readonly int $userId,
        public readonly UserLogRoleInterface&BackedEnum $role,
        public readonly ActivityLogTypeInterface&BackedEnum $type,
        public readonly AppLogModuleInterface&BackedEnum $module,
        public readonly ActionLogInterface&BackedEnum  $action,
        public readonly ?string $description = null,
        public readonly ?int $refId = null,
        public readonly ?string $ip = null,
        public readonly ?string $userAgent = null,
    ) {}
}
