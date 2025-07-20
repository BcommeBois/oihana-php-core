<?php

namespace oihana\core\arrays ;

/**
 * Recursively merges multiple arrays.
 * String keys in later arrays will overwrite values from earlier arrays.
 * Numeric keys will have their values appended, maintaining the order.
 * @param array ...$arrays The arrays to be merged.
 * @return array The deeply merged array.
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function deepMerge( array ...$arrays ): array
{
    $merged = [];
    foreach ( $arrays as $array )
    {
        foreach ( $array as $key => $value )
        {
            if ( is_array($value) && isset($merged[$key]) && is_array($merged[$key]) )
            {
                $merged[ $key ] = deepMerge( $merged[ $key ] , $value );
            }
            elseif ( is_int($key) )
            {
                $merged[] = $value ;
            }
            else
            {
                $merged[ $key ] = $value ;
            }
        }
    }
    return $merged;
}