<?php

namespace oihana\core\arrays ;

/**
 * Takes a value and turns it into an array of that value, unless the value is already an array.
 * @param mixed $value The value to encapsulate in an array.
 * @return array
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function toArray( mixed $value ): array
{
    if( is_array( $value ) )
    {
        return $value ;
    }
    return [ $value ] ;
}