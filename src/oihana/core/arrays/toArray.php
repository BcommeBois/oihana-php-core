<?php

namespace oihana\core\arrays ;

/**
 * Ensures the given value is returned as an array.
 *
 * If the value is already an array, it is returned unchanged.
 * Otherwise, the value is wrapped inside a new array.
 *
 * @param mixed $value The value to encapsulate in an array.
 *
 * @return array The value wrapped in an array, or the original array if already an array.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\toArray;
 *
 * $a = toArray(123);
 * print_r($a); // [123]
 *
 * $b = toArray("hello");
 * print_r($b); // ["hello"]
 *
 * $c = toArray([1, 2, 3]);
 * print_r($c); // [1, 2, 3]
 *
 * $d = toArray(null);
 * print_r($d); // [null]
 * ```
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