<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted by a number of years, clamping day overflow.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. Delegates
 * to `addMonths( $date , $years * 12 )`, so a leap day clamps the same way : `2028-02-29`
 * + 1 year lands on `2029-02-28`, 2029 not being a leap year, rather than overflowing to
 * `2029-03-01`.
 *
 * @param DateTimeInterface $date  The source date.
 * @param int               $years The number of years to add (negative to subtract).
 *
 * @return DateTimeImmutable A new immutable date shifted by `$years` years, day-clamped.
 *
 * @example
 * ```php
 * use function oihana\core\date\addYears;
 *
 * addYears( new DateTimeImmutable( '2026-01-15' ) , 1 )  ; // 2027-01-15
 * addYears( new DateTimeImmutable( '2028-02-29' ) , 1 )  ; // 2029-02-28 (clamped, 2029 is not a leap year)
 * addYears( new DateTimeImmutable( '2026-01-15' ) , -1 ) ; // 2025-01-15
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function addYears( DateTimeInterface $date , int $years ): DateTimeImmutable
{
    return addMonths( $date , $years * 12 ) ;
}
