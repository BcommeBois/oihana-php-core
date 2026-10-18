<?php

namespace oihana\core\date ;

use function oihana\core\date\durationToSeconds ;

/**
 * Breaks a duration down into days, hours, minutes and seconds.
 *
 * The `$duration` may be anything {@see durationToSeconds()} reads:
 * - an int|float number of seconds (e.g. `3725`),
 * - a colon string `"MM:SS"` or `"HH:MM:SS"`,
 * - a unit string `"1.5d 3h 15m 12.5s"` (any subset, any order),
 * - or `null` (a zero duration).
 *
 * The duration is first normalized into a number of seconds, so every component stays within its
 * natural range — seconds and minutes below 60, hours below `$hoursPerDay` — whatever the input
 * form: `"90:00"` gives 1 hour and 30 minutes. Only the seconds may carry a fractional part
 * (`5.5`); a whole number of seconds comes back as an `int`.
 *
 * The parts are keyed by the {@see DurationUnit} suffixes, so the key a caller reads is the suffix
 * {@see humanizeDuration()} writes. With `$hoursPerDay` at `0` or below, no day is counted and every
 * hour stays in the hours.
 *
 * @param int|float|string|null $duration    The duration to break down.
 * @param int                   $hoursPerDay Hours in a day for day↔hour conversion (default 24).
 *
 * @return array{d:int,h:int,m:int,s:int|float} The days, hours, minutes and seconds, keyed by
 *                                              `DurationUnit::DAY`, `HOUR`, `MINUTE` and `SECOND`.
 *
 * @example
 * ```php
 * use oihana\core\date\DurationUnit ;
 * use function oihana\core\date\durationParts ;
 *
 * durationParts( 3725 ) ;     // [ 'd' => 0 , 'h' => 1 , 'm' => 2 , 's' => 5 ]
 * durationParts( '90:00' ) ;  // [ 'd' => 0 , 'h' => 1 , 'm' => 30 , 's' => 0 ]
 * durationParts( 5.5 ) ;      // [ 'd' => 0 , 'h' => 0 , 'm' => 0 , 's' => 5.5 ]
 *
 * durationParts( 93784 )[ DurationUnit::DAY ] ; // 1
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function durationParts( int|float|string|null $duration = null , int $hoursPerDay = 24 ) : array
{
    $total = durationToSeconds( $duration , $hoursPerDay ) ;

    $secondsPerDay = $hoursPerDay > 0 ? $hoursPerDay * 3600 : 0 ;

    $days = 0 ;
    if ( $secondsPerDay > 0 )
    {
        $days   = (int) floor( $total / $secondsPerDay ) ;
        $total -= $days * $secondsPerDay ;
    }

    $hours   = (int) floor( $total / 3600 ) ; $total -= $hours   * 3600 ;
    $minutes = (int) floor( $total / 60 )   ; $total -= $minutes * 60 ;

    // Drop floating-point noise then collapse a whole number of seconds to an int.
    $seconds = round( $total , 6 ) ;
    if ( $seconds == (int) $seconds )
    {
        $seconds = (int) $seconds ;
    }

    return
    [
        DurationUnit::DAY    => $days    ,
        DurationUnit::HOUR   => $hours   ,
        DurationUnit::MINUTE => $minutes ,
        DurationUnit::SECOND => $seconds ,
    ] ;
}
