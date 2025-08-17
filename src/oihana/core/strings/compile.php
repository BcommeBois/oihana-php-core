<?php

namespace oihana\core\strings ;

use Stringable;

use function oihana\core\arrays\clean;

/**
 * Compiles a string or an array of expressions into a single string.
 *
 * - If the input is an array, the values are cleaned with {@see \oihana\core\arrays\clean()}
 *   (removing empty or null entries) and then concatenated with the given separator.
 * - If the input is a string, it is returned as is.
 * - If the input is null or any other type, an empty string is returned.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.0
 *
 * @param string|Stringable|array|null $expressions The expression(s) to compile.
 * @param string                       $separator   The separator used when joining array values (default is a single space).
 *
 * @return string The compiled expression.
 *
 * @example
 * ```php
 * use function oihana\core\expressions\compile;
 *
 * echo compile( [ 'foo' , '' , 'bar' ] )        . PHP_EOL; // 'foo bar'
 * echo compile( [ 'a'   , null , 'c' ] , ', ' ) . PHP_EOL; // 'a, c'
 * echo compile( 'baz' )                         . PHP_EOL; // 'baz'
 * echo compile( null )                          . PHP_EOL; // ''
 * ```
 */
function compile( string|Stringable|array|null $expressions , string $separator = ' ' ) :string
{
    if( is_array( $expressions ) && count( $expressions ) > 0 )
    {
        $expressions = clean( $expressions ) ;
        return empty( $expressions ) ? '' : implode( $separator , $expressions ) ;
    }

    if ( is_string( $expressions ) || $expressions instanceof Stringable )
    {
        return (string) $expressions ;
    }

    return '' ;
}