<?php

namespace oihana\core\date ;

/**
 * Tells whether a date format pattern carries a **literal** `Z`, the ISO-8601 designator for UTC.
 *
 * A pattern ending on an escaped `Z` — `'Y-m-d\TH:i:s.v\Z'`, the default of
 * {@see formatDateTime()} — asserts that the moment it renders is expressed in UTC.
 * That is what lets `formatDateTime()` convert the moment before formatting it, rather
 * than stamping a wall clock with a suffix that lies about it.
 *
 * The distinction the naive `str_contains( $format , '\Z' )` misses is the escaped
 * backslash : in `'\\\\Z'` the backslash escapes a backslash, leaving `Z` as the native
 * token for the timezone offset in seconds — not a Zulu designator. Walking the pattern
 * and stepping over whatever follows an escape tells the two apart.
 *
 * @param string $format A format string compatible with DateTime::format().
 *
 * @return bool True when the pattern contains an escaped `Z`, false otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\date\hasLiteralZulu;
 *
 * var_dump( hasLiteralZulu( 'Y-m-d\TH:i:s.v\Z' ) ) ; // true  — Zulu designator
 * var_dump( hasLiteralZulu( 'Y-m-d\TH:i:sP'    ) ) ; // false — a real offset token
 * var_dump( hasLiteralZulu( 'Y-m-d\TH:i:s'     ) ) ; // false — no timezone at all
 * var_dump( hasLiteralZulu( 'Z'                ) ) ; // false — the native offset token
 * var_dump( hasLiteralZulu( '\\\\Z'            ) ) ; // false — escaped backslash, then the token
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function hasLiteralZulu( string $format ): bool
{
    $length = strlen( $format ) ;

    for ( $i = 0 ; $i < $length ; $i++ )
    {
        if ( $format[ $i ] !== '\\' )
        {
            continue ;
        }

        $i++ ;

        if ( $i < $length && $format[ $i ] === 'Z' )
        {
            return true ;
        }
    }

    return false ;
}
