<?php

namespace oihana\core\strings ;

use oihana\enums\Char;
use Throwable;

/**
 * Quick and fast format of a string using indexed parameters only.
 * <p>Usage :</p>
 * <ul>
 * <li><code>fastFormat( $pattern, ...args ):String</code></li>
 * <li><code>fastFormat( $pattern, [arg0,arg1,arg2,...] )</code></li>
 * </ul>
 * <pre>
 * use function core\strings\fastFormat ;
 *
 * echo fastFormat( "hello {0}", "world" ) );
 * //output: "hello world"
 *
 * echo fastFormat( "hello {0} {1} {2}", [ "the", "big", "world" ] ) );
 * //output: "hello the big world"
 * </pre>
 * @return string The formatted string expression.
 */
function fastFormat( ?string $pattern , ...$args ) :string
{
    if( !isset( $pattern ) || ( $pattern == "" ) )
    {
        return "" ;
    }

    if( count( $args ) && is_array( $args[0] ) )
    {
        $args = $args[0];
    }

    if ( empty($args) )
    {
        return $pattern;
    }

    // Search the tags {0}, {1}, etc.
    return preg_replace_callback( '/\\{(\d+)\\}/' , function ( $matches ) use ( $args )
    {
        $index = (int) $matches[1] ;

        if ( !isset( $args[ $index ] ) )
        {
            return $matches[0];
        }

        $value = $args[ $index ] ;

        if ( is_object( $value ) )
        {
            if ( method_exists( $value , '__toString' ) )
            {
                return (string) $value ;
            }
            else
            {
                $toStringFunc = $value->__toString ?? null ;
                if( isset( $toStringFunc ) && is_callable( $toStringFunc ) )
                {
                    return $toStringFunc() ;
                }
                return Char::LEFT_BRACKET . 'object ' . get_class($value) . Char::RIGHT_BRACKET ;
            }
        }

        return (string) $value;
    }
    ,
    $pattern ) ;
}