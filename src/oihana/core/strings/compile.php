<?php

namespace oihana\core\strings ;

use Stringable;

use function oihana\core\arrays\clean;

/**
 * Compiles a string, object, boolean, or an array of expressions into a single string.
 *
 * Behavior:
 * - If the input is an array, the values are cleaned with {@see clean()}
 * (removing empty or null entries), optionally transformed with a callback,
 * then recursively compiled and concatenated using the specified separator.
 * - If the input is a string, it is returned as is.
 * - If the input is an object implementing {@see Stringable}, it is cast to string.
 * - If the input is any other object, it is converted to JSON using {@see json_encode}.
 * - Booleans are converted to `'true'` or `'false'`.
 * - Null or unsupported types are converted to an empty string.
 *
 * @param string|Stringable|array|null $expressions The expression(s) to compile.
 * @param string                       $separator   The separator used when joining array values (default is a single space).
 * @param callable|null                $callback    An optional callback applied to each array element before compiling. Signature: `function(mixed $item): mixed`.
 *
 * @return string The compiled string expression.
 *
 * @author  Marc Alcaraz
 * @since   1.0.0
 *
 * @package oihana\core\strings
 * @example
 * ```php
 * use function oihana\core\expressions\compile;
 *
 * echo compile( [ 'foo' , '' , 'bar' ] )        . PHP_EOL; // 'foo bar'
 * echo compile( [ 'a'   , null , 'c' ] , ', ' ) . PHP_EOL; // 'a, c'
 * echo compile( 'baz' )                         . PHP_EOL; // 'baz'
 * echo compile( null )                          . PHP_EOL; // ''
 * echo compile([1, 2, 3], '-', fn($v) => $v*2)  . PHP_EOL; // '2-4-6'
 * ```
 */
function compile( mixed $expressions , string $separator = ' ' , ?callable $callback = null ) :string
{
    if( is_array( $expressions ) )
    {
        $expressions = clean( $expressions ) ;
        if ( count( $expressions ) === 0 )
        {
            return '' ;
        }

        if ( $callback !== null )
        {
            $expressions = array_map( $callback , $expressions ) ;
        }

        $expressions = array_map( fn( $item ) => compile( $item , $separator , $callback ) , $expressions ) ;
        return implode( $separator , $expressions ) ;
    }

    if ( $expressions instanceof Stringable )
    {
        return (string) $expressions ;
    }

    if ( is_object( $expressions ) )
    {
        return json_encode( $expressions ) ;
    }

    if ( is_bool( $expressions ) )
    {
        return $expressions ? 'true' : 'false';
    }

    if ( is_null( $expressions ) )
    {
        return '' ;
    }

    return (string) $expressions ;
}