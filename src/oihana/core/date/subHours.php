<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted backwards by a number of hours.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. Equivalent
 * to `addHours( $date , -$hours )` — see `addHours()` for the elapsed-time semantics
 * across a DST transition.
 *
 * @param DateTimeInterface $date  The source date.
 * @param int               $hours The number of hours to subtract.
 *
 * @return DateTimeImmutable A new immutable date shifted backwards by `$hours` hours.
 *
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\subHours;
 *
 * subHours( new DateTimeImmutable( '2026-01-01 05:00:00' ) , 5 ) ; // 2026-01-01 00:00:00
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function subHours( DateTimeInterface $date , int $hours ): DateTimeImmutable
{
    return addHours( $date , -$hours ) ;
}
