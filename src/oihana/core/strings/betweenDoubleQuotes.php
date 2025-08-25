<?php

declare(strict_types=1);

namespace oihana\core\strings;

/**
 * Wraps an expression in double quotes or a custom character.
 *
 * - Strings are wrapped directly.
 * - Arrays are concatenated with the given separator, then wrapped.
 * - Wrapping can be disabled with the `$useQuotes` flag.
 *
 * @param mixed   $expression  The value to wrap (string, array, or any type convertible to string).
 * @param string  $char        The quote or character to wrap around the expression (default: single quote `'`).
 * @param bool    $useQuotes   Whether to wrap the expression (default: true).
 * @param string  $separator   Separator used to join array elements (default: `' '`).
 *
 * @return string The resulting wrapped expression.
 *
 * @example
 * ```php
 * betweenQuotes('world');        // returns "world"
 * betweenQuotes(['foo', 'bar']); // returns "foo bar"
 * ```
 *
 * @package oihana\core\strings
 * @since   1.0.6
 * @author  Marc Alcaraz
 */
function betweenDoubleQuotes(  mixed $expression = '' , string $char = '"' , bool $useQuotes = true , string $separator = ' ' ): string
{
    return between( $expression , $char , $char , $useQuotes , $separator ) ;
}
