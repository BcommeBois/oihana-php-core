<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted backwards by a number of years, clamping day overflow.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. Equivalent
 * to `addYears( $date , -$years )` — see `addYears()` for the day-clamping semantics.
 *
 * @param DateTimeInterface $date  The source date.
 * @param int               $years The number of years to subtract.
 *
 * @return DateTimeImmutable A new immutable date shifted backwards by `$years` years, day-clamped.
 *
 * @example
 * ```php
 * use function oihana\core\date\subYears;
 *
 * subYears( new DateTimeImmutable( '2026-01-15' ) , 1 ) ; // 2025-01-15
 * subYears( new DateTimeImmutable( '2028-02-29' ) , 1 ) ; // 2027-02-28 (clamped, 2027 is not a leap year)
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function subYears( DateTimeInterface $date , int $years ): DateTimeImmutable
{
    return addYears( $date , -$years ) ;
}
