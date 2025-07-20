<?php

namespace oihana\core\strings ;

use oihana\core\strings\helpers\SnakeCache;

/**
 * Converts a string to snake_case (or a custom delimiter).
 *
 * This function transforms camelCase, PascalCase, and space-separated words
 * into snake case or any delimiter specified.
 *
 * It uses an internal cache (via `SnakeCache`) to optimize repeated calls with the same input.
 * The cache can be flushed by calling `SnakeCache::flush()`.
 *
 * @param ?string $source The input string to convert.
 * @param string $delimiter The delimiter to use (default is underscore '_').
 * @return string The converted snake_case (or custom delimiter) string.
 *
 * @example
 * ```php
 * echo snake("helloWorld");     // Outputs: "hello_world"
 * echo snake("HelloWorld");     // Outputs: "hello_world"
 * echo snake("Hello World");    // Outputs: "hello_world"
 * echo snake("helloWorld", "-"); // Outputs: "hello-world"
 * ```
 *
 * Clear the internal cache if needed
 * ```php
 * use oihana\core\strings\helpers\SnakeCache;
 * SnakeCache::flush();
 * ```
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