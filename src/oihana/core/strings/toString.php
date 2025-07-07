<?php

namespace oihana\core\strings ;

use Stringable;

use function oihana\core\arrays\flatten;

/**
 * Converts a value to string.
 * An empty string is returned for null values.
 * The sign of -0.0 is preserved as "-0".
 *
 * @param mixed $value The value to convert.
 * @return string The converted string.
 */
function toString( mixed $value): string
{
    if ( $value === null )
    {
        return '' ;
    }

    if ( is_string( $value ) || $value instanceof Stringable )
    {
        return $value ;
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