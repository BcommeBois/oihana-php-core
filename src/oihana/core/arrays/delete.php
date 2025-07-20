<?php

namespace oihana\core\arrays ;

/**
 * Unset a key or nested key in an array using dot notation.
 *
 * - If the key is '*', clears the whole array.
 * - Supports string path ("a.b.c") or array path (['a','b','c']).
 * - If the path is partially invalid, the array is returned unchanged.
 *
 * @param mixed        $target    The array to modify (ignored if not an array).
 * @param string|array $key       The key path to delete.
 * @param string       $separator The separator for string key paths (default: '.').
 *
 * @return mixed The modified array (or original if not an array).
 *
 * @example
 * ```php
 * $data =
 * [
 *     'user' =>
 *     [
 *         'profile' =>
 *         [
 *             'name' => 'Alice',
 *             'age' => 30
 *         ],
 *         'active' => true
 *     ]
 * ];
 *
 * // Remove a nested key
 * $result = delete($data, 'user.profile.age');
 * print_r($result);
 * // [
 * //     'user' => [
 * //         'profile' => ['name' => 'Alice'],
 * //         'active'  => true
 * //     ]
 * // ]
 *
 * // Remove a top-level key
 * $result = delete($data, 'user.active');
 *
 * // Remove all keys
 * $result = delete($data, '*');
 * print_r($result); // []
 *
 * // Using array path
 * $result = delete($data, ['user', 'profile', 'name']);
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function delete( mixed $target , string|array $key , string $separator = '.' ) :mixed
{
    if ( !is_array( $target ) )
    {
        return $target ;
    }

    $segments = is_array( $key ) ? $key : explode( $separator , $key ) ;
    $segment  = array_shift($segments );

    if( $segment == '*' ) // ALL
    {
        return [] ;
    }

    if ( !array_key_exists( $segment , $target ) )
    {
        return $target;
    }

    if ( count( $segments ) > 0)
    {
        $target[$segment] = delete( $target[ $segment ] , $segments , $separator ) ;
    }
    else
    {
        unset( $target[$segment] ) ; // Final segment: delete it
    }
    return $target;
}