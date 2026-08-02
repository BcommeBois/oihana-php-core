<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted backwards by a number of months, clamping day overflow.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. Equivalent
 * to `addMonths( $date , -$months )` — see `addMonths()` for the day-clamping semantics.
 *
 * @param DateTimeInterface $date   The source date.
 * @param int               $months The number of months to subtract.
 *
 * @return DateTimeImmutable A new immutable date shifted backwards by `$months` months, day-clamped.
 *
 * @example
 * ```php
 * use function oihana\core\date\subMonths;
 *
 * subMonths( new DateTimeImmutable( '2026-03-15' ) , 1 ) ; // 2026-02-15
 * subMonths( new DateTimeImmutable( '2026-03-31' ) , 1 ) ; // 2026-02-28 (clamped)
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function subMonths( DateTimeInterface $date , int $months ): DateTimeImmutable
{
    return addMonths( $date , -$months ) ;
}
