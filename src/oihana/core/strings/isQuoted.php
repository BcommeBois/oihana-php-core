<?php

declare(strict_types=1);

namespace oihana\core\strings ;

/**
 * Checks whether a string is wrapped in a matching pair of quote characters.
 *
 * Recognised pairs (symmetric and asymmetric):
 * - `'…'`  single quotes
 * - `"…"`  double quotes
 * - `` `…` `` backticks
 * - `«…»`  French guillemets
 * - `“…”`  English typographic double quotes
 * - `‘…’`  English typographic single quotes / apostrophe
 *
 * Returns `false` when the value is shorter than the pair, or when only
 * one side is quoted.
 *
 * @param string $value The string to test.
 *
 * @return bool `true` if the value starts AND ends with a matching pair.
 *
 * @example
 * ```php
 * isQuoted('"hello"'); // true
 * isQuoted("'hello'"); // true
 * isQuoted('«hello»'); // true
 * isQuoted('"hello');  // false (unmatched)
 * isQuoted('hello');   // false
 * isQuoted('""');       // true (empty quoted string)
 * isQuoted('"');        // false (too short)
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.8
 *
 * @see unquote()
 * @see getQuoteChar()
 */
function isQuoted( string $value ) :bool
{
    if ( strlen( $value ) < 2 )
    {
        return false ;
    }

    static $pairs =
    [
        "'" => "'" ,
        '"' => '"' ,
        '`' => '`' ,
        '«' => '»' ,
        '“' => '”' ,
        '‘' => '’' ,
    ];

    return array_any
    (
        $pairs ,
        fn( $right , $left ) => strlen( $value ) >= strlen( $left ) + strlen( $right )
                                && str_starts_with ( $value , $left  )
                                && str_ends_with   ( $value , $right )
    );
}
