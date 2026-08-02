<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;
use InvalidArgumentException ;

/**
 * Returns the last representable instant of the week containing a date.
 *
 * A new `DateTimeImmutable` is returned, set to `23:59:59.999999` (microseconds
 * included) on the last day of the week ; the source date is never modified. The
 * timezone of the returned value is the one carried by `$date`.
 *
 * `$firstDayOfWeek` follows the same ISO-8601 numbering as `startOfWeek()` — see there
 * for details. The last day of the week is `$firstDayOfWeek + 6` (mod 7).
 *
 * @param DateTimeInterface $date           The source date.
 * @param int                $firstDayOfWeek The first day of the week, `1` (Monday) to `7` (Sunday). Default `1`.
 *
 * @return DateTimeImmutable A new immutable date, at the end of the week containing `$date`.
 *
 * @throws InvalidArgumentException If `$firstDayOfWeek` is outside `[1, 7]`.
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\endOfWeek;
 *
 * // 2026-03-18 is a Wednesday.
 * endOfWeek( new DateTimeImmutable( '2026-03-18 14:30:45' ) )    ; // 2026-03-22 23:59:59.999999 (Sunday)
 * endOfWeek( new DateTimeImmutable( '2026-03-18 14:30:45' ) , 7 ); // 2026-03-21 23:59:59.999999 (Saturday)
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function endOfWeek( DateTimeInterface $date , int $firstDayOfWeek = 1 ): DateTimeImmutable
{
    return addDays( startOfWeek( $date , $firstDayOfWeek ) , 6 )->setTime( 23 , 59 , 59 , 999999 ) ;
}
