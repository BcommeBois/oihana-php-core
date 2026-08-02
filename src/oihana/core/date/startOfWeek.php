<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;
use InvalidArgumentException ;

/**
 * Returns the first representable instant of the week containing a date.
 *
 * A new `DateTimeImmutable` is returned, set to `00:00:00.000000` on the first day of
 * the week ; the source date is never modified. The timezone of the returned value is
 * the one carried by `$date`.
 *
 * `$firstDayOfWeek` uses the ISO-8601 numbering also returned by `DateTime::format( 'N' )`
 * : `1` for Monday through `7` for Sunday. It defaults to `1` (ISO-8601 / most of the
 * world) ; pass `7` for the US/Canada convention (week starts on Sunday).
 *
 * @param DateTimeInterface $date           The source date.
 * @param int                $firstDayOfWeek The first day of the week, `1` (Monday) to `7` (Sunday). Default `1`.
 *
 * @return DateTimeImmutable A new immutable date, at the start of the week containing `$date`.
 *
 * @throws InvalidArgumentException If `$firstDayOfWeek` is outside `[1, 7]`.
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\startOfWeek;
 *
 * // 2026-03-18 is a Wednesday.
 * startOfWeek( new DateTimeImmutable( '2026-03-18 14:30:45' ) )    ; // 2026-03-16 00:00:00.000000 (Monday)
 * startOfWeek( new DateTimeImmutable( '2026-03-18 14:30:45' ) , 7 ); // 2026-03-15 00:00:00.000000 (Sunday)
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function startOfWeek( DateTimeInterface $date , int $firstDayOfWeek = 1 ): DateTimeImmutable
{
    if ( $firstDayOfWeek < 1 || $firstDayOfWeek > 7 )
    {
        throw new InvalidArgumentException
        (
            "\$firstDayOfWeek must be between 1 (Monday) and 7 (Sunday), $firstDayOfWeek given."
        ) ;
    }

    $date = DateTimeImmutable::createFromInterface( $date ) ;

    $currentDayOfWeek = (int) $date->format( 'N' ) ; // 1 (Monday) .. 7 (Sunday)
    $daysSinceStart   = ( $currentDayOfWeek - $firstDayOfWeek + 7 ) % 7 ;

    return addDays( $date , -$daysSinceStart )->setTime( 0 , 0 , 0 , 0 ) ;
}
