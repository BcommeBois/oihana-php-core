<?php

declare(strict_types=1);

namespace oihana\core\strings;

/**
 * Encapsulates an expression between braces '{..}'
 *
 * - If the expression is a string, it is wrapped in braces.
 * - If the expression is an array, its values are concatenated with the given separator, then wrapped in braces.
 * - If `$useParentheses` is false, the expression is returned without wrapping.
 *
 * @param mixed   $expression  The expression to wrap.
 * @param bool    $useBraces Whether to apply the braces.
 * @param string  $separator   Separator for arrays.
 *
 * @return string The wrapped expression.
 *
 * @example
 * ```php
 * betweenBraces('id: 1');           // '{ id: 1 }'
 * betweenBraces(['x', 'y']);        // '{ x y }'
 * betweenBraces(['x', 'y'], false); // 'x y'
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function betweenBraces(  mixed $expression = '' , bool $useBraces = true , string $separator = ' ' ): string
{
    return between( $expression , left: '{' , right: '}' , flag: $useBraces , separator: $separator ) ;
}
