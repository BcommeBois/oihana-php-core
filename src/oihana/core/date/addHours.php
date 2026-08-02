<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted by a number of hours.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. This is
 * absolute-duration (elapsed-time) arithmetic : across a DST transition, `addHours()`
 * shifts by exactly that many real hours, which can land on a different wall-clock time
 * than a calendar shift of the same nominal span. For example, from
 * `2026-03-29 01:30:00 Europe/Paris` (just before the spring-forward gap), `addHours( $d , 1 )`
 * lands on `03:30:00 +02:00` — a two-hour wall-clock jump for one real hour elapsed —
 * where `addDays( $d , 1 )` keeps the same wall-clock time on the next day. A negative
 * `$hours` shifts the date backwards.
 *
 * @param DateTimeInterface $date  The source date.
 * @param int               $hours The number of hours to add (negative to subtract).
 *
 * @return DateTimeImmutable A new immutable date shifted by `$hours` hours.
 *
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\addHours;
 *
 * addHours( new DateTimeImmutable( '2026-01-01 00:00:00' ) , 5 )  ; // 2026-01-01 05:00:00
 * addHours( new DateTimeImmutable( '2026-01-01 00:00:00' ) , -1 ) ; // 2025-12-31 23:00:00
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function addHours( DateTimeInterface $date , int $hours ): DateTimeImmutable
{
    return DateTimeImmutable::createFromInterface( $date )->modify( $hours . ' hours' ) ;
}
