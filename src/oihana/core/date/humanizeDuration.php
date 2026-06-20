<?php

namespace oihana\core\date ;

use function oihana\core\date\durationToSeconds ;

/**
 * Returns a human-readable representation of a duration, e.g. `"1d 2h 3m 4s"`.
 *
 * The `$duration` may be:
 * - an int|float number of seconds (e.g. `3725`),
 * - a colon string `"MM:SS"` or `"HH:MM:SS"`,
 * - a unit string `"1.5d 3h 15m 12.5s"` (any subset, any order),
 * - or `null` (treated as a zero duration → `"0s"`).
 *
 * Whatever the input form, the duration is first normalized into a number of
 * seconds and then broken down so that every component stays within its natural
 * range (seconds < 60, minutes < 60, hours < `$hoursPerDay`). Fractional input
 * therefore rolls up correctly: `"90:00"` and `"1.5h"` both yield `"1h 30m"`.
 * Only the seconds component may carry a fractional part (e.g. `"5.5s"`).
 *
 * @param int|float|string|null $duration    The duration to format.
 * @param int                   $hoursPerDay Hours in a day for day↔hour conversion (default 24).
 *
 * @return string The human-readable duration (e.g. `"1h 42m"`), `"0s"` for an empty duration.
 *
 * @example
 * ```php
 * use function oihana\core\date\humanizeDuration ;
 *
 * echo humanizeDuration( 3725 ) ;        // "1h 2m 5s"
 * echo humanizeDuration( '1h 30m' ) ;    // "1h 30m"
 * echo humanizeDuration( '90:00' ) ;     // "1h 30m"
 * echo humanizeDuration( null ) ;        // "0s"
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function humanizeDuration( int|float|string|null $duration = null , int $hoursPerDay = 24 ) : string
{
    $total = durationToSeconds( $duration , $hoursPerDay ) ;

    $secondsPerHour = $hoursPerDay > 0 ? $hoursPerDay * 3600 : 0 ;

    $days = 0 ;
    if ( $secondsPerHour > 0 )
    {
        $days   = (int) floor( $total / $secondsPerHour ) ;
        $total -= $days * $secondsPerHour ;
    }

    $hours   = (int) floor( $total / 3600 ) ; $total -= $hours   * 3600 ;
    $minutes = (int) floor( $total / 60 )   ; $total -= $minutes * 60 ;

    // Drop floating-point noise then collapse a whole number of seconds to an int.
    $seconds = round( $total , 6 ) ;
    if ( $seconds == (int) $seconds )
    {
        $seconds = (int) $seconds ;
    }

    $output = '' ;

    if ( $seconds > 0 || ( $days === 0 && $hours === 0 && $minutes === 0 ) )
    {
        $output .= $seconds . DurationUnit::SECOND ;
    }
    if ( $minutes > 0 ) { $output = $minutes . DurationUnit::MINUTE . ' ' . $output ; }
    if ( $hours   > 0 ) { $output = $hours   . DurationUnit::HOUR   . ' ' . $output ; }
    if ( $days    > 0 ) { $output = $days    . DurationUnit::DAY    . ' ' . $output ; }

    return trim( $output ) ;
}
