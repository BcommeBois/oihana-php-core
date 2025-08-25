<?php

declare(strict_types=1);

namespace oihana\core\strings;

/**
 * Encapsulates an expression between specific characters.
 *
 * @param mixed       $expression The expression to encapsulate between two characters.
 * @param string      $left       The left character.
 * @param string|null $right      The right character. If null, uses the left character.
 * @param bool        $flag       Indicates whether to apply the wrapping.
 * @param string      $separator  The separator used to join arrays.
 *
 * @return mixed The wrapped string or original expression.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.6
 *
 * @example
 * ```php
 * echo between( 'x' , '<' , '>' ) ; // '<x>'
 * echo between( [ 'a' , 'b' ], '[' , ']' ) ; // '[a b]'
 * echo between( 'y' , '"' , null , false ) ; // 'y'
 * ```
 */
function between( mixed $expression = null, string $left = '', ?string $right = null, bool $flag = true , string $separator = ' ' ): mixed
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
        if( empty( $expression ) )
        {
            $expression = '' ;
        }
        $expression = compile( $expression , $separator ) ;
    }

    return $flag ? ( $left . $expression . $right ) : $expression ;
}
