<?php

namespace oihana\core\arrays ;

/**
 * Ensures that a given key in an array contains a sub-array, and returns it by reference.
 *
 * If the key does not exist or is not an array, it initializes it as an empty array.
 * This is useful for building nested structures dynamically using references.
 *
 * @param array  &$current The parent array passed by reference.
 * @param string $segment  The key to check or initialize as an array.
 *
 * @return array A reference to the array stored at the specified key.
 *
 * @example
 * ```php
 * $data = [];
 * $sub =& ensureArrayPath( $data , 'user' ) ;
 * $sub['name'] = 'Alice';
 * // $data now becomes: ['user' => ['name' => 'Alice']]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function &ensureArrayPath( array &$current , string $segment ): array
{
    if ( !isset( $current[ $segment ] ) || !is_array( $current[ $segment ] ) )
    {
        $current[ $segment ] = [];
    }

    return $current[ $segment ] ;
}