<?php

namespace oihana\core\strings ;

use oihana\core\strings\helpers\SnakeCache;

/**
 * Convert a string to snake case.
 *
 * @param ?string $source The string expression to format.
 * @return string The snake string.
 *
 * @example
 * <p>Basic Examples: </p>
 * <code>
 * echo snake("helloWorld"); // Outputs: "hello_world"
 * echo snake("HelloWorld"); // Outputs: "hello_world"
 * echo snake("Hello World"); // Outputs: "hello_world"
 * echo snake('helloWorld', '-'); // Outputs : "hello-world"
 * </code>
 *
 * <p>Flush the snake cache</p>
 * <p>The method use an internal cache by default, you can flush the snake cache with the static SnakeCache class.</p>
 * <code>
 * use oihana\core\strings\helpers\SnakeCache ;
 * SnakeCache::flush() ;
 * </code>
 */
function snake( ?string $source , string $delimiter = '_' ) : string
{
    if( !is_string( $source ) || $source === '' )
    {
        return '' ;
    }

    $key = $source ;

    if ( SnakeCache::has( $key , $delimiter ) )
    {
        return SnakeCache::get( $key , $delimiter ) ;
    }

    if ( !ctype_lower( $source ) )
    {
        $source = preg_replace('/\s+/u', '', ucwords( $source ) ) ;
        $source = lower( preg_replace('/(.)(?=[A-Z])/u' , '$1' . $delimiter, $source ) );
    }

    SnakeCache::set( $key , $delimiter , $source );

    return $source ;
}