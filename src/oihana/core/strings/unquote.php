<?php

declare(strict_types=1);

namespace oihana\core\strings ;

/**
 * Strips a single layer of surrounding matching quote characters from a string.
 *
 * Recognised pairs (symmetric and asymmetric):
 * - `'…'`  single quotes
 * - `"…"`  double quotes
 * - `` `…` `` backticks
 * - `«…»`  French guillemets
 * - `“…”`  English typographic double quotes
 * - `‘…’`  English typographic single quotes / apostrophe
 *
 * No-op when:
 * - the value is empty,
 * - the value does not start AND end with a matching pair,
 * - only one side is quoted (`"foo`, `foo"`).
 *
 * Only one layer is removed (`""foo""` becomes `"foo"`, not `foo`).
 * No escape decoding is performed inside the value.
 *
 * @param string $value The string to unquote.
 *
 * @return string The unquoted string, or the original value if no matching pair is found.
 *
 * @example
 * ```php
 * unquote('"hello"');   // 'hello'
 * unquote("'hello'");   // 'hello'
 * unquote('`hello`');   // 'hello'
 * unquote('«hello»');   // 'hello'
 * unquote('"hello');    // '"hello'   (no-op, unmatched)
 * unquote('""');         // ''
 * unquote('');           // ''
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.8
 */
function unquote( string $value ) :string
{
    if ( $value === '' )
    {
        return $value ;
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

    $opener = array_find_key
    (
        $pairs ,
        fn( $right , $left ) => strlen( $value ) >= strlen( $left ) + strlen( $right )
            && str_starts_with( $value , $left )
            && str_ends_with( $value , $right )
    );

    return $opener === null
        ? $value
        : substr( $value , strlen( $opener ) , -strlen( $pairs[ $opener ] ) ) ;
}
