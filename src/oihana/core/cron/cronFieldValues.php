<?php

namespace oihana\core\cron ;

/**
 * Expands one field of a cron expression into the values it matches.
 *
 * Reads what a hand-written crontab holds: a star, a number, a range `a-b`, a step written with a
 * slash after a star or a range (`*`, `0-12` followed by `/` and the step), and any comma-separated
 * mix of those. A reader that handles only the first two silently misreads a six-hourly schedule
 * as a daily one.
 *
 * **Returns `null` rather than guessing**: a value out of `[$min, $max]`, a range written
 * backwards, a zero step, a name (`mon`, `jan`) or a preset (`@daily`) give null. A field that
 * cannot be read is a schedule whose promise is unknown, and a caller should say so rather than
 * take it for any particular schedule.
 *
 * @param string $field The field, e.g. `*`, `3`, `1-5`, `0,30`, or a step over a star or a range.
 * @param int    $min   The lowest legal value for this field (0 for minutes and hours, 1 for days of
 *                      the month and months, 0 for days of the week).
 * @param int    $max   The highest legal value for this field (59, 23, 31, 12, and 7 for days of the
 *                      week, where both 0 and 7 are Sunday).
 *
 * @return list<int>|null The matching values, ascending and each once; null when the field cannot
 *                        be read.
 *
 * @example
 * ```php
 * use function oihana\core\cron\cronFieldValues;
 *
 * cronFieldValues( '1-5'  , 0 , 7 ) ;   // [ 1 , 2 , 3 , 4 , 5 ]
 * cronFieldValues( '0,30' , 0 , 59 ) ;  // [ 0 , 30 ]
 * cronFieldValues( '25'   , 0 , 23 ) ;  // null — out of range
 * ```
 *
 * @package oihana\core\cron
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.4.0
 */
function cronFieldValues( string $field , int $min , int $max ) :?array
{
    // An empty field, or an empty item in a list, matches none of the forms below: null.

    $values = [] ;

    foreach ( explode( ',' , trim( $field ) ) as $part )
    {
        $step  = 1 ;
        $range = $part ;

        if ( str_contains( $part , '/' ) )
        {
            [ $range , $rawStep ] = explode( '/' , $part , 2 ) ;

            if ( !ctype_digit( $rawStep ) || (int) $rawStep < 1 )
            {
                return null ;
            }

            $step = (int) $rawStep ;
        }

        if ( $range === '*' )
        {
            $from = $min ;
            $to   = $max ;
        }
        elseif ( str_contains( $range , '-' ) )
        {
            [ $low , $high ] = explode( '-' , $range , 2 ) ;

            if ( !ctype_digit( $low ) || !ctype_digit( $high ) )
            {
                return null ;
            }

            $from = (int) $low ;
            $to   = (int) $high ;
        }
        elseif ( ctype_digit( $range ) )
        {
            $from = (int) $range ;
            $to   = $from ;
        }
        else
        {
            return null ;
        }

        if ( $from < $min || $to > $max || $from > $to )
        {
            return null ;
        }

        for ( $value = $from ; $value <= $to ; $value += $step )
        {
            $values[ $value ] = true ;
        }
    }

    $values = array_keys( $values ) ;

    sort( $values ) ;

    return $values ;
}
