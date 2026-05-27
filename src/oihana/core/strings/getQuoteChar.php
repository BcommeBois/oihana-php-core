<?php

declare(strict_types=1);

namespace oihana\core\strings ;

/**
 * Returns the opening quote character used to wrap a string, or `null`.
 *
 * Useful when you need to know *which* quote style was used in order to
 * round-trip a value (unquote → transform → rewrap with the same style).
 *
 * Recognised pairs:
 * - `'…'`  single quotes        → returns `"'"`
 * - `"…"`  double quotes        → returns `'"'`
 * - `` `…` `` backticks         → returns `'`'`
 * - `«…»`  French guillemets    → returns `'«'`
 * - `“…”`  English typographic  → returns `'“'`
 * - `‘…’`  English typographic  → returns `'‘'`
 *
 * @param string $value The string to inspect.
 *
 * @return string|null The opening quote character, or `null` if the value
 *                     is not wrapped in a known matching pair.
 *
 * @example
 * ```php
 * getQuoteChar('"hello"'); // '"'
 * getQuoteChar("'hello'"); // "'"
 * getQuoteChar('«hello»'); // '«'
 * getQuoteChar('hello');   // null
 * getQuoteChar('"hello');  // null
 * getQuoteChar('"');        // null (too short)
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.8
 *
 * @see isQuoted()
 * @see unquote()
 */
function getQuoteChar( string $value ) :?string
{
    if ( strlen( $value ) < 2 )
    {
        return null ;
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

    return array_find_key  ( $pairs , fn( $right , $left ) => strlen( $value ) >= strlen( $left ) + strlen( $right )
        && str_starts_with ( $value , $left )
        && str_ends_with   ( $value , $right ) ) ;
}
