<?php

declare(strict_types=1);

namespace oihana\core\strings;

/**
 * Encapsulates an expression between brackets '[..]'
 *
 * - If the expression is a string, it is wrapped in brackets.
 * - If the expression is an array, its values are concatenated with the given separator, then wrapped in brackets.
 * - If `$useParentheses` is false, the expression is returned without wrapping.
 *
 * @param mixed   $expression  The expression to wrap.
 * @param bool    $useBrackets Whether to apply the brackets.
 * @param string  $separator   Separator for arrays.
 * @param bool    $trim        Whether to trim existing `$left`/`$right` characters (default: true).
 *
 * @return string The wrapped expression.
 *
 * @example
 * ```php
 * betweenBrackets( [ 'a' , 'b' ] ) ;     // '[a b]'
 * betweenBrackets( 'index: 3' ) ;       // '[index: 3]'
 * betweenBrackets( 'value' , false ) ;  // 'value'
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.6
 */
function betweenBrackets
(
    mixed  $expression  = '' ,
    bool   $useBrackets = true ,
    string $separator   = ' ' ,
    bool   $trim        = true
)
:string
{
    return between( $expression , left: '[' , right: ']' , flag: $useBrackets , separator: $separator , trim: $trim ) ;
}
