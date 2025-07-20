<?php

namespace oihana\core\arrays ;

/**
 * Indicates if all the keys in an array are integers.
 *
 * This function checks if every key in the provided array is an integer.
 * Returns true only if *all* keys are integers, false otherwise.
 *
 * @param array $array The array to check.
 * @return bool True if all keys are integers, false otherwise.
 *
 * @example
 * ```php
 * $arr1 = [10 => 'a', 20 => 'b', 30 => 'c'];
 * var_dump(hasIntKeys($arr1)); // true
 *
 * $arr2 = ['0' => 'a', 1 => 'b', 2 => 'c'];
 * var_dump(hasIntKeys($arr2)); // false, because '0' is string key, not int
 *
 * $arr3 = ['foo' => 'bar', 42 => 'baz'];
 * var_dump(hasIntKeys($arr3)); // false
 *
 * $arr4 = [];
 * var_dump(hasIntKeys($arr4)); // true (no keys, so vacuously true)
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function hasIntKeys( array $array ) :bool
{
    return array_all( array_keys( $array ) , fn( $key ) => is_int( $key ) ) ;
}