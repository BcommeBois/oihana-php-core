<?php

declare(strict_types=1);

namespace oihana\core\strings ;

/**
 * Strips a single layer of surrounding `"…"` double quotes from a string.
 *
 * Compatible with RFC 7230 `quoted-string` unwrapping, without decoding
 * `\` quoted-pair escapes inside the value (rare for header parameters
 * in practice).
 *
 * No-op when:
 * - the value is shorter than 2 characters,
 * - the value does not start AND end with `"`,
 * - only one side is quoted.
 *
 * @param string $value The string to strip.
 *
 * @return string The string with one outer pair of double quotes removed,
 *                or the original value if no matching pair is found.
 *
 * @example
 * ```php
 * stripDoubleQuotes('"hello"');   // 'hello'
 * stripDoubleQuotes('"');          // '"'    (too short)
 * stripDoubleQuotes('"hello');    // '"hello' (unmatched)
 * stripDoubleQuotes('hello"');    // 'hello"' (unmatched)
 * stripDoubleQuotes('""');         // ''
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.8
 */
function stripDoubleQuotes( string $value ) :string
{
    if ( strlen( $value ) >= 2 && $value[ 0 ] === '"' && $value[ -1 ] === '"' )
    {
        return substr( $value , 1 , -1 ) ;
    }

    return $value ;
}
