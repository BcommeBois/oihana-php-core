<?php

namespace oihana\core\strings ;

use Stringable;

use function oihana\core\arrays\flatten;

/**
 * Converts a value to a string representation.
 *
 * - Returns an empty string for `null` values.
 * - Preserves the sign of `-0.0` as the string "-0".
 * - Converts arrays recursively by flattening and joining with commas.
 * - Accepts objects implementing `Stringable`.
 *
 * @param mixed $value The value to convert.
 * @return string The string representation of the value.
 *
 * @example
 * ```php
 * echo toString(null);        // Outputs: ""
 * echo toString("hello");     // Outputs: "hello"
 * echo toString(123);         // Outputs: "123"
 * echo toString(-0.0);        // Outputs: "-0"
 * echo toString([1, 2, 3]);   // Outputs: "1,2,3"
 * echo toString([[1, 2], 3]); // Outputs: "1,2,3"
 * ```
 *
 * With Stringable object:
 * ```php
 * class Foo implements Stringable
 * {
 *     public function __toString() { return "foo"; }
 * }
 * echo toString(new Foo()); // Outputs: "foo"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function toString( mixed $value): string
{
    if ( $value === null )
    {
        return '' ;
    }

    if ( is_string($value) )
    {
        return $value ;
    }

    if ( $value instanceof Stringable )
    {
        return (string) $value ;
    }

    if ( is_array( $value ) )
    {
        $value = flatten( $value ) ;
        return implode(',' , array_map( fn( $item ) => toString( $item ) , $value ) ) ;
    }

    if ( $value === 0.0 && sprintf('%.1f', $value) === '-0.0' )
    {
        return '-0' ;
    }

    return (string) $value ;
}