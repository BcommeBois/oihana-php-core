<?php

namespace oihana\core\date ;

use function oihana\core\date\durationParts ;

/**
 * Returns a duration in clock notation — hours, minutes and seconds joined by colons, e.g. `"1:42"`.
 *
 * The `$duration` may be anything {@see durationToSeconds()} reads: a number of seconds, a colon
 * string (`"MM:SS"`, `"HH:MM:SS"`), a unit string (`"1h 42m"`) or `null` (a zero duration). It is
 * normalized first, then broken down by {@see durationParts()}: `"90:00"` reads as 90 minutes and
 * is written `"1:30:00"` — minutes never exceed 59.
 *
 * The days are folded into the hours (a day and an hour give `"25:00:00"`), and a component only
 * gets its leading zero behind a larger one:
 *
 * | Duration    | Output      | With `$zeroFill` |
 * |-------------|-------------|------------------|
 * | 28 s        | `"28"`      | `"0:00:28"`      |
 * | 102 s       | `"1:42"`    | `"0:01:42"`      |
 * | 42 min      | `"42:00"`   | `"0:42:00"`      |
 * | 1 h 42 min  | `"1:42:00"` | `"1:42:00"`      |
 * | 65.5 s      | `"1:05.5"`  | `"0:01:05.5"`    |
 * | 0 / null    | `"0"`       | `"0:00:00"`      |
 *
 * With `$zeroFill`, the hours and the minutes are always written, so durations line up in a column.
 * Only the seconds may carry a fractional part.
 *
 * @param int|float|string|null $duration    The duration to format.
 * @param bool                  $zeroFill    Always write the hours and the minutes (default false).
 * @param int                   $hoursPerDay Hours in a day for day↔hour conversion (default 24).
 *
 * @return string The duration in clock notation, `"0"` for an empty duration.
 *
 * @example
 * ```php
 * use function oihana\core\date\formatDuration ;
 *
 * echo formatDuration( 102 ) ;           // "1:42"
 * echo formatDuration( 28 , true ) ;     // "0:00:28"
 * echo formatDuration( '1h 42m' ) ;      // "1:42:00"
 * echo formatDuration( '90:00' ) ;       // "1:30:00"
 * echo formatDuration( 90000 ) ;         // "25:00:00"
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function formatDuration( int|float|string|null $duration = null , bool $zeroFill = false , int $hoursPerDay = 24 ) : string
{
    $parts = durationParts( $duration , $hoursPerDay ) ;

    $hours   = $parts[ DurationUnit::HOUR ] + $parts[ DurationUnit::DAY ] * max( $hoursPerDay , 0 ) ;
    $minutes = $parts[ DurationUnit::MINUTE ] ;
    $seconds = $parts[ DurationUnit::SECOND ] ;

    $larger = $minutes > 0 || $hours > 0 || $zeroFill ;

    if ( $seconds > 0 )
    {
        $output = ( $seconds < 10 && $larger ? '0' : '' ) . $seconds ;
    }
    else
    {
        $output = $larger ? '00' : '0' ;
    }

    if ( $minutes > 0 )
    {
        $output = ( $minutes <= 9 && ( $hours > 0 || $zeroFill ) ? '0' : '' ) . $minutes . DurationUnit::SEPARATOR . $output ;
    }
    elseif ( $hours > 0 || $zeroFill )
    {
        $output = '00' . DurationUnit::SEPARATOR . $output ;
    }

    if ( $hours > 0 )
    {
        $output = $hours . DurationUnit::SEPARATOR . $output ;
    }
    elseif ( $zeroFill )
    {
        $output = '0' . DurationUnit::SEPARATOR . $output ;
    }

    return $output ;
}
