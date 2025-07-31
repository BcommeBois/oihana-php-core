<?php

namespace oihana\core\arrays ;

/**
 * Recursively merges multiple arrays.
 *
 * - Associative keys (strings) are merged deeply : sub-arrays are merged récursivement.
 * - Numeric keys (ints) are appended, maintaining order.
 *
 * @param array ...$arrays The arrays to be merged.
 * @return array The deeply merged array.
 *
 * @example
 * ```php
 * $a = ['user' => ['name' => 'Alice', 'roles' => ['admin']]];
 * $b = ['user' => ['roles' => ['editor'], 'active' => true]];
 * $c = ['tags' => ['php', 'dev']];
 *
 * $merged = deepMerge($a, $b, $c);
 *
 * print_r($merged);
 * // [
 * //     'user' => [
 * //         'name'   => 'Alice',
 * //         'roles'  => ['admin', 'editor'],
 * //         'active' => true
 * //     ],
 * //     'tags' => ['php', 'dev']
 * // ]
 * ```
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
            if ( is_array( $value ) && isset( $merged[$key] ) && is_array( $merged[$key] ) )
            {
                $merged[ $key ] = deepMerge( $merged[ $key ] , $value );
            }
            elseif ( is_int( $key ) )
            {
                if ( !in_array($value, $merged, true) )
                {
                    $merged[] = $value;
                }
            }
            else
            {
                $merged[ $key ] = $value ;
            }
        }
    }
    return $merged;
}