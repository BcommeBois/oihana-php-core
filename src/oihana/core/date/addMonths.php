<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted by a number of months, clamping day overflow.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. The
 * wall-clock time of day is preserved.
 *
 * Unlike `DateTimeImmutable::modify( '+1 month' )`, which overflows into the following
 * month when the source day does not exist in the target month (`2026-01-31` + 1 month
 * natively lands on `2026-03-03`, February having only 28 days), this function clamps
 * the day to the last day of the target month (`2026-02-28`). This is the behaviour of
 * every mainstream date library ; the raw, overflowing behaviour stays one
 * `->modify( '+1 month' )` call away when it is genuinely what is wanted.
 *
 * @param DateTimeInterface $date   The source date.
 * @param int               $months The number of months to add (negative to subtract).
 *
 * @return DateTimeImmutable A new immutable date shifted by `$months` months, day-clamped.
 *
 * @example
 * ```php
 * use function oihana\core\date\addMonths;
 *
 * addMonths( new DateTimeImmutable( '2026-01-15' ) , 1 )  ; // 2026-02-15
 * addMonths( new DateTimeImmutable( '2026-01-31' ) , 1 )  ; // 2026-02-28 (clamped, not 2026-03-03)
 * addMonths( new DateTimeImmutable( '2024-01-31' ) , 1 )  ; // 2024-02-29 (leap year)
 * addMonths( new DateTimeImmutable( '2026-03-31' ) , -1 ) ; // 2026-02-28 (clamped)
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function addMonths( DateTimeInterface $date , int $months ): DateTimeImmutable
{
    $date = DateTimeImmutable::createFromInterface( $date ) ;

    $totalMonths = ( (int) $date->format( 'Y' ) ) * 12 + ( (int) $date->format( 'n' ) - 1 ) + $months ;

    $targetYear  = (int) floor( $totalMonths / 12 ) ;
    $targetMonth = $totalMonths - $targetYear * 12 + 1 ; // 1..12

    $targetDay = min( (int) $date->format( 'j' ) , daysInGregorianMonth( $targetYear , $targetMonth ) ) ;

    return $date->setDate( $targetYear , $targetMonth , $targetDay ) ;
}

/**
 * Returns the number of days in a given month of the proleptic Gregorian calendar.
 *
 * Pure arithmetic — no string parsing — so it is defined for any integer year, including
 * zero and negative ones, where `new DateTimeImmutable( "$year-$month-01" )` is not : PHP's
 * date parser rejects a handful of short negative-year forms (`"-005-01-01"` and the like)
 * as ambiguous.
 *
 * @param int $year  Any integer year.
 * @param int $month Month number, 1 (January) to 12 (December).
 *
 * @return int The number of days in that month (28 to 31).
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 *
 * @internal
 */
function daysInGregorianMonth( int $year , int $month ): int
{
    /** @var array<int, int> $days */
    static $days = [ 31 , 28 , 31 , 30 , 31 , 30 , 31 , 31 , 30 , 31 , 30 , 31 ] ;

    if ( $month === 2 && isLeapYear( $year ) )
    {
        return 29 ;
    }

    return $days[ $month - 1 ] ;
}
