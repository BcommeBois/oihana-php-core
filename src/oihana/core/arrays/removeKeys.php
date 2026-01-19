<?php

namespace oihana\core\arrays ;

/**
 * Removes a set of keys from an array.
 *
 * This function removes the specified keys from the given array.
 * If `$clone` is set to `true`, the original array is not modified; instead, a new array is returned.
 * Otherwise, the original array is modified directly (passed by value).
 *
 * @param array $array The input array to modify or clone.
 * @param array $keys An array of keys to remove from the input array.
 * @param bool $clone If true, operates on a copy of the array and returns it; otherwise, modifies the array in place (by value).
 *
 * @return array The resulting array with specified keys removed.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\removeKeys;
 *
 * $input = ['name' => 'Alice', 'email' => 'alice@example.com', 'age' => 30];
 *
 * // Example 1: Remove one key (in-place behavior)
 * $result = removeKeys($input, ['email']);
 * // $result = ['name' => 'Alice', 'age' => 30]
 *
 * // Example 2: Remove multiple keys
 * $result = removeKeys($input, ['name', 'age']);
 * // $result = ['email' => 'alice@example.com']
 *
 * // Example 3: Use clone to preserve the original array
 * $original = ['a' => 1, 'b' => 2, 'c' => 3];
 * $copy = removeKeys($original, ['a'], true);
 * // $original remains unchanged: ['a' => 1, 'b' => 2, 'c' => 3]
 * // $copy = ['b' => 2, 'c' => 3]
 *
 * // Example 4: Key not found (no error)
 * $result = removeKeys(['x' => 1], ['y']);
 * // $result = ['x' => 1]
 *
 * // Example 5: Empty key list
 * $result = removeKeys(['foo' => 'bar'], []);
 * // $result = ['foo' => 'bar']
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function removeKeys
(
    array &$array ,
    array $keys   = [] ,
    bool  $clone  = true
)
:array
{
    $ar = $clone ? [ ...$array ] : $array;

    foreach ( $keys as $key )
    {
        if ( array_key_exists( $key , $ar ) )
        {
            unset( $ar[ $key ] ) ;
        }
    }

    if ( !$clone )
    {
        $array = $ar;
    }

    return $ar ;
}