<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 09:50
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Manager;

use Maatify\MongoActivity\DTO\ActivityRecordDTO;
use Maatify\MongoActivity\Repository\ActivityRepository;

/**
 * Class ActivityManager
 *
 * Responsible for orchestrating and recording user/system activities
 * into MongoDB using the `maatify:mongo-activity` library.
 *
 * This manager acts as the main entry point for saving activity logs
 * — it transforms an `ActivityRecordDTO` into a structured MongoDB record.
 *
 * ### Example Usage:
 * ```php
 * use Maatify\MongoActivity\Manager\ActivityManager;
 * use Maatify\MongoActivity\DTO\ActivityRecordDTO;
 * use Maatify\MongoActivity\Enum\ActivityTypeEnum;
 *
 * $activityManager = new ActivityManager($activityRepository);
 *
 * $dto = new ActivityRecordDTO(
 *     userId: 501,
 *     role: 'admin',
 *     type: ActivityTypeEnum::DELETE,
 *     module: 'Users',
 *     action: 'Remove user account',
 *     description: 'Admin removed a deactivated user',
 *     refId: 501,
 *     ip: '192.168.1.11',
 *     userAgent: $_SERVER['HTTP_USER_AGENT'] ?? 'CLI'
 * );
 *
 * $activityManager->record($dto);
 * ```
 *
 * @package Maatify\MongoActivity\Manager
 */
final class ActivityManager
{
    /**
     * @param ActivityRepository $repo Repository instance for MongoDB operations.
     */
    public function __construct(private readonly ActivityRepository $repo) {}

    /**
     * 🧾 Record a new activity in MongoDB.
     *
     * @param ActivityRecordDTO $dto  Data transfer object representing the activity details.
     *
     * Stores a new document in the MongoDB activity collection with the following structure:
     * - user_id
     * - role
     * - type
     * - module
     * - action
     * - description
     * - ref_id
     * - ip
     * - user_agent
     * - created_at (ISO-8601)
     *
     * @return void
     */
    public function record(ActivityRecordDTO $dto): void
    {
        $this->repo->insert([
            'user_id'    => $dto->userId,
            'role'       => $dto->role->value,
            'type'       => $dto->type->value,
            'module'     => $dto->module->value,
            'action'     => $dto->action,
            'description'=> $dto->description,
            'ref_id'     => $dto->refId,
            'ip'         => $dto->ip,
            'user_agent' => $dto->userAgent,
            'created_at' => date('c'),
        ]);
    }
}
