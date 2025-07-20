<?php

namespace oihana\core\arrays ;

/**
 * Flattens a nested array into a single-level array.
 * This function recursively iterates through the nested arrays,
 * concatenating all nested values into one flat array.
 *
 * @param array $array The array to flatten.
 *
 * @return array The flattened array, containing all nested values in order.
 *
 * @example
 * ```php
 * $nested = [1, [2, 3], [[4], 5], 6];
 * $flat = flatten($nested);
 * // Result: [1, 2, 3, 4, 5, 6]
 *
 * $complex = ['a', ['b', ['c', 'd']], 'e'];
 * $flat = flatten($complex);
 * // Result: ['a', 'b', 'c', 'd', 'e']
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function flatten( array $array ): array
{
    $result = [];

    foreach ($array as $item)
    {
        if ( is_array( $item ) )
        {
            $result = array_merge( $result , flatten( $item ) );
        }
        else
        {
            $result[] = $item ;
        }
    }

    return $result;
}