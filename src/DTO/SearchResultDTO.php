<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 13:23
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\DTO;

/**
 * Class SearchResultDTO
 *
 * Represents structured paginated search results returned from MongoDB activity logs.
 * This DTO standardizes the response format for both repositories and API endpoints.
 *
 * Example usage:
 * ```php
 * $result = new SearchResultDTO($data, 1, 20, 134, 7, 'user_activities', 'current');
 * return $result->toArray();
 * ```
 */
final class SearchResultDTO
{
    /**
     * @param array  $data         The activity documents returned from MongoDB.
     * @param int    $page         Current pagination page.
     * @param int    $perPage      Number of items per page.
     * @param int    $total        Total number of matching records.
     * @param int    $totalPages   Computed total number of pages.
     * @param string $collection   MongoDB collection name where the data originated.
     * @param string $periodType   Describes whether this result is from the "current" or "archived" period.
     */
    public function __construct(
        public readonly array $data,
        public readonly int $page,
        public readonly int $perPage,
        public readonly int $total,
        public readonly int $totalPages,
        public readonly string $collection,
        public readonly string $periodType,
    ) {}

    /**
     * Convert the DTO into an associative array suitable for API responses.
     *
     * @return array{
     *     data: array,
     *     meta: array{
     *         page: int,
     *         per_page: int,
     *         total: int,
     *         total_pages: int,
     *         collection: string,
     *         period_type: string
     *     }
     * }
     */
    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => [
                'page'         => $this->page,
                'per_page'     => $this->perPage,
                'total'        => $this->total,
                'total_pages'  => $this->totalPages,
                'collection'   => $this->collection,
                'period_type'  => $this->periodType,
            ],
        ];
    }
}
