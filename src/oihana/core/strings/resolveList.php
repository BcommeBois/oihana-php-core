<?php

namespace oihana\core\strings ;

/**
 * Resolves a list from a string or an array :
 * - splits the string by the given separator
 * - trims each element
 * - removes empty elements
 * - joins the list using the specified replacement
 * - returns $default if the resulting list is empty
 *
 * @param string|array|null $value     Input value (string or array)
 * @param string            $separator Separator for input string (default ';')
 * @param string            $replace   Separator for output string (default PHP_EOL)
 * @param string|null       $default   Default value if the result is empty
 *
 * @return string|null Normalized string or default
 *
 * @example
 * ```php
 * echo resolveList("a;b;c");
 * // Output:
 * // "a\nb\nc"
 *
 * echo resolveList(";;;  ", ";", "\n", "fallback");
 * // Output:
 * // "fallback"
 *
 * echo resolveList(["foo", "  bar  ", "", "baz"], ",", "\n");
 * // Output:
 * // "foo\nbar\nbaz"
 * ```
 */
function resolveList
(
    string|array|null $value ,
    string            $separator = ';' ,
    string            $replace   = PHP_EOL ,
    ?string           $default   = null
)
: ?string
{
    if ( $value === null )
    {
        return $default ;
    }

    $parts = is_string( $value ) ? explode( $separator , $value ) : $value ;
    $parts = array_filter( array_map(fn($v) => trim( (string) $v ) , $parts ) , fn($v) => $v !== '' ) ;

    if ( empty( $parts ) )
    {
        return $default ;
    }

    return implode( $replace , $parts ) ;
}