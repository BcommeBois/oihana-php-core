<?php

namespace oihana\core\arrays ;

/**
 * Indicates if all the keys in an array are strings.
 *
 * This function checks if every key in the provided array is a string.
 * Returns true only if *all* keys are strings, false otherwise.
 *
 * @param array $array The array to check.
 * @return bool True if all keys are strings, false otherwise.
 *
 * @example
 * ```php
 * $arr1 = ['foo' => 1, 'bar' => 2, 'baz' => 3];
 * var_dump(hasStringKeys($arr1)); // true
 *
 * $arr2 = ['0' => 'a', '1' => 'b', '2' => 'c'];
 * var_dump(hasStringKeys($arr2)); // true, keys are strings '0', '1', '2'
 *
 * $arr3 = [0 => 'zero', 1 => 'one', 2 => 'two'];
 * var_dump(hasStringKeys($arr3)); // false, keys are integers
 *
 * $arr4 = ['foo' => 'bar', 42 => 'baz'];
 * var_dump(hasStringKeys($arr4)); // false, mixed keys (string and int)
 *
 * $arr5 = [];
 * var_dump(hasStringKeys($arr5)); // true (empty array, vacuously true)
 * ```
 */
function hasStringKeys( array $array ) :bool
{
    return array_all( array_keys( $array ) , fn( $key ) => is_string( $key ) ) ;
}