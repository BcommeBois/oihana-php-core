<?php

declare(strict_types=1);

namespace oihana\core\strings;

/**
 * Encapsulates an expression between parentheses (`()`).
 *
 * - If the expression is a string, it is wrapped in parentheses.
 * - If the expression is an array, its values are concatenated with the given separator, then wrapped in parentheses.
 * - If `$useParentheses` is false, the expression is returned without wrapping.
 *
 * @param mixed   $expression     The expression to wrap.
 * @param bool    $useParentheses Whether to apply the parentheses.
 * @param string  $separator      Separator for arrays.
 * @param bool    $trim           Whether to trim existing `$left`/`$right` characters (default: true).
 *
 * @return string The wrapped expression.
 *
 * @example
 * ```php
 * betweenParentheses( 'sum: 10' ) ;       // '(sum: 10)'
 * betweenParentheses( ['a', 'b', 'c'] ) ; // '(a b c)'
 * betweenParentheses( 'val', false ) ;   // 'val'
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.6
 */
function betweenParentheses
(
    mixed  $expression     = '' ,
    bool   $useParentheses = true ,
    string $separator      = ' ' ,
    bool   $trim           = true
)
:string
{
    return between( $expression , left: '(' , right: ')' , flag: $useParentheses , separator: $separator , trim: $trim ) ;
}
