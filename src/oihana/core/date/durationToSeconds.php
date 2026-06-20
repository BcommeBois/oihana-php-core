<?php

namespace oihana\core\date ;

/**
 * Normalizes a duration expressed in any supported form into a number of seconds.
 *
 * The `$duration` may be:
 * - an int|float number of seconds (e.g. `3725`), returned as-is (cast to float),
 * - a colon string `"MM:SS"` or `"HH:MM:SS"`,
 * - a unit string `"1.5d 3h 15m 12.5s"` (any subset, any order, decimals allowed),
 * - or `null` (treated as a zero duration → `0.0`).
 *
 * A day is worth `$hoursPerDay` hours, so the `d` unit of a unit string is
 * converted accordingly (a colon string never carries a day component).
 *
 * @param int|float|string|null $duration    The duration to convert.
 * @param int                   $hoursPerDay Hours in a day for the `d` unit (default 24).
 *
 * @return float The total number of seconds (`0.0` for an empty duration).
 *
 * @example
 * ```php
 * use function oihana\core\date\durationToSeconds ;
 *
 * durationToSeconds( 3725 ) ;        // 3725.0
 * durationToSeconds( '90:00' ) ;     // 5400.0
 * durationToSeconds( '1h 30m' ) ;    // 5400.0
 * durationToSeconds( '1.5d' , 8 ) ;  // 43200.0  (1.5 × 8h)
 * durationToSeconds( null ) ;        // 0.0
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function durationToSeconds( int|float|string|null $duration = null , int $hoursPerDay = 24 ) : float
{
    if ( $duration === null )
    {
        return 0.0 ;
    }

    if ( is_numeric( $duration ) )
    {
        return (float) $duration ;
    }

    $seconds = 0.0 ;

    if ( str_contains( $duration , ':' ) )
    {
        $parts = explode( ':' , $duration ) ;

        if ( count( $parts ) === 2 )
        {
            $seconds += (int)   $parts[0] * 60 ;
            $seconds += (float) $parts[1] ;
        }
        elseif ( count( $parts ) === 3 )
        {
            $seconds += (int)   $parts[0] * 3600 ;
            $seconds += (int)   $parts[1] * 60 ;
            $seconds += (float) $parts[2] ;
        }

        return $seconds ;
    }

    if ( preg_match( '/(\d+(?:\.\d+)?)\s*' . DurationUnit::DAY . '/i' , $duration , $m ) )
    {
        $seconds += (float) $m[1] * $hoursPerDay * 3600 ;
    }
    if ( preg_match( '/(\d+(?:\.\d+)?)\s*' . DurationUnit::HOUR . '/i' , $duration , $m ) )
    {
        $seconds += (float) $m[1] * 3600 ;
    }
    if ( preg_match( '/(\d+(?:\.\d+)?)\s*' . DurationUnit::MINUTE . '/i' , $duration , $m ) )
    {
        $seconds += (float) $m[1] * 60 ;
    }
    if ( preg_match( '/(\d+(?:\.\d+)?)\s*' . DurationUnit::SECOND . '/i' , $duration , $m ) )
    {
        $seconds += (float) $m[1] ;
    }

    return $seconds ;
}
