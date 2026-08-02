<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted by a number of minutes.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. This is
 * absolute-duration (elapsed-time) arithmetic : across a DST transition, `addMinutes()`
 * shifts by exactly that many real minutes, which can land on a different wall-clock
 * time than a calendar shift of the same nominal span (see `addDays()`). A negative
 * `$minutes` shifts the date backwards.
 *
 * @param DateTimeInterface $date    The source date.
 * @param int               $minutes The number of minutes to add (negative to subtract).
 *
 * @return DateTimeImmutable A new immutable date shifted by `$minutes` minutes.
 *
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\addMinutes;
 *
 * addMinutes( new DateTimeImmutable( '2026-01-01 00:00:00' ) , 90 ) ; // 2026-01-01 01:30:00
 * addMinutes( new DateTimeImmutable( '2026-01-01 00:00:00' ) , -1 ) ; // 2025-12-31 23:59:00
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function addMinutes( DateTimeInterface $date , int $minutes ): DateTimeImmutable
{
    return DateTimeImmutable::createFromInterface( $date )->modify( $minutes . ' minutes' ) ;
}
