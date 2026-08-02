<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted backwards by a number of minutes.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. Equivalent
 * to `addMinutes( $date , -$minutes )` — see `addMinutes()` for the elapsed-time
 * semantics across a DST transition.
 *
 * @param DateTimeInterface $date    The source date.
 * @param int               $minutes The number of minutes to subtract.
 *
 * @return DateTimeImmutable A new immutable date shifted backwards by `$minutes` minutes.
 *
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\subMinutes;
 *
 * subMinutes( new DateTimeImmutable( '2026-01-01 01:30:00' ) , 90 ) ; // 2026-01-01 00:00:00
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function subMinutes( DateTimeInterface $date , int $minutes ): DateTimeImmutable
{
    return addMinutes( $date , -$minutes ) ;
}
