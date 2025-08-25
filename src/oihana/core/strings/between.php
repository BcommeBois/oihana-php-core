<?php

declare(strict_types=1);

namespace oihana\core\strings;

/**
 * Encapsulates an expression between specific characters.
 *
 * Wraps the expression with `$left` and `$right`.
 *
 * Handles arrays by joining their values with `$separator`.
 * Leading/trailing `$left` or `$right` characters are trimmed to avoid duplication.
 *
 * - If `$expression` is an array, its values are concatenated with `$separator`.
 * - If `$right` is null, it defaults to the value of `$left`.
 * - If `$flag` is false, no wrapping is applied.
 *
 * @param mixed       $expression The expression to encapsulate (string, array, or any type convertible to string).
 * @param string      $left       The left string to prepend.
 * @param string|null $right      The right string to append. Defaults to `$left` if null.
 * @param bool        $flag       Whether to apply the wrapping (default: true).
 * @param string      $separator  Separator used when joining array values (default: space).
 * @param bool        $trim       Whether to trim existing `$left`/`$right` characters (default: true).
 *
 * @return string The wrapped expression if `$flag` is true; otherwise the original expression as string.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.6
 *
 * @example
 * ```php
 * echo between('x', '<', '>');          // '<x>'
 * echo between(['a', 'b'], '[', ']');  // '[a b]'
 * echo between('y', '"', null, false); // 'y'
 * echo between('[test]', '[', ']');    // '[test]' (trims duplicate brackets)
 * ```
 */
function between
(
    mixed  $expression = null ,
    string $left       = ''   ,
   ?string $right      = null ,
    bool   $flag       = true ,
    string $separator  = ' '  ,
    bool   $trim       = true
)
:string
{
    if ( is_null( $right ) )
    {
        $right = $left ;
    }

    if ( is_null( $expression ) )
    {
        $expression = '' ;
    }

    if ( is_array( $expression ) )
    {
        $expression = empty( $expression ) ? '' : compile( $expression , $separator ) ;
    }
    else
    {
        $expression = (string) $expression ;
    }

    if ( !$flag )
    {
        return $expression;
    }

    if ( $trim )
    {
        $expression = ltrim( $expression , $left  ) ;
        $expression = rtrim( $expression , $right ) ;
    }

    return $left . $expression . $right ;
}
