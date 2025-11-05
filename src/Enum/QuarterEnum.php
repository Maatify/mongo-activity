<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 11:31
 * Project: maatify:mongo-activity
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\MongoActivity\Enum;

/**
 * Enum QuarterEnum
 *
 * Represents the four quarters of a year (Q1–Q4)
 * and provides a helper method to determine the quarter from a month.
 */
enum QuarterEnum: string
{
    case Q1 = 'Q1'; // January–March
    case Q2 = 'Q2'; // April–June
    case Q3 = 'Q3'; // July–September
    case Q4 = 'Q4'; // October–December

    /**
     * Determine the quarter based on the given month number (1–12).
     *
     * @param int $month Month number (1 for January, 12 for December)
     *
     * @return self The corresponding quarter enum (Q1–Q4)
     */
    public static function fromMonth(int $month): self
    {
        return match (true) {
            $month <= 3  => self::Q1,
            $month <= 6  => self::Q2,
            $month <= 9  => self::Q3,
            default      => self::Q4,
        };
    }
}
