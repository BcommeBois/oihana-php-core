<?php

declare(strict_types=1);

namespace oihana\core\strings;

/**
 * Wraps an expression with spaces on both sides.
 *
 * Calls `between()` with space characters as `$left` and `$right`. Handles strings, arrays, and optional suppression of spaces.
 *
 * - **String**: Simply prepends and appends a space.
 * - **Array**: Concatenates array values using the specified `$separator`,
 *   then wraps the resulting string in spaces.
 * - **Other types**: Converted to string before wrapping.
 * - **If `$useSpaces` is `false`**, the expression is returned as-is without any surrounding spaces.
 *
 * @param mixed  $expression  The value to wrap. Can be string, array, or any type convertible to string.
 * @param bool   $useSpaces   Whether to apply the surrounding spaces (default: `true`).
 * @param string $separator   Separator to use when `$expression` is an array (default: `' '`).
 * @param bool   $trim        Whether to trim existing `$left`/`$right` characters (default: true).
 *
 * @return string The wrapped expression with spaces if `$useSpaces` is true; otherwise the original expression as string.
 *
 * @example
 * ```php
 * betweenSpaces('hello');                    // returns ' hello '
 * betweenSpaces('hello', false);             // returns 'hello'
 * betweenSpaces(['a', 'b', 'c']);            // returns ' a b c '
 * betweenSpaces(['a', 'b', 'c'], true, ','); // returns ' a,b,c '
 * betweenSpaces(123);                        // returns ' 123 '
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.6
 */
function betweenSpaces
(
    mixed  $expression = '' ,
    bool   $useSpaces  = true ,
    string $separator  = ' ' ,
    bool   $trim       = true
)
:string
{
    return between( $expression , left: ' ' , right: ' ' , flag: $useSpaces , separator: $separator , trim: $trim ) ;
}
