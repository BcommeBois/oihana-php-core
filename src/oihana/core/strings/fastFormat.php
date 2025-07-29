<?php

namespace oihana\core\strings ;

/**
 * Quickly formats a string using indexed placeholders and arguments.
 *
 * This function replaces indexed tokens like `{0}`, `{1}`, etc. in a pattern string
 * with corresponding values from a list of arguments or an array.
 *
 * - If arguments are passed as a list: `fastFormat("{0} {1}", "hello", "world")`
 * - If passed as a single array: `fastFormat("{0} {1}", ["hello", "world"])`
 *
 * @param string|null $pattern The format string containing indexed placeholders.
 * @param mixed ...$args       The values to insert, either as variadic args or a single array.
 *
 * @return string The formatted string with placeholders replaced by values.
 *
 * @example
 * ```php
 * echo fastFormat("Hello {0}", "World");
 * // Output: "Hello World"
 *
 * echo fastFormat("Coordinates: {0}, {1}", [45.0, -73.0]);
 * // Output: "Coordinates: 45, -73"
 *
 * echo fastFormat("User {0} has {1} points", "Alice", 1500);
 * // Output: "User Alice has 1500 points"
 *
 * echo fastFormat("Missing: {0}, {1}", "only one");
 * // Output: "Missing: only one, {1}" (missing index stays unchanged)
 *
 * class Person {
 *     public function __toString(): string {
 *         return "John Doe";
 *     }
 * }
 * echo fastFormat("Name: {0}", new Person());
 * // Output: "Name: John Doe"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function fastFormat( ?string $pattern , ...$args ) :string
{
    if( !isset( $pattern ) || ( $pattern === "" ) )
    {
        return "" ;
    }

    if( count( $args ) && is_array( $args[0] ) )
    {
        $args = $args[0];
    }

    if ( empty( $args ) )
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
                return '[object ' . get_class($value) . ']' ;
            }
        }

        return (string) $value;
    }
    ,
    $pattern ) ;
}