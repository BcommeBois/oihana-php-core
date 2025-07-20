<?php

namespace oihana\core\arrays ;

/**
 * Swaps two indexed values in a specific array representation.
 *
 * Supports both integer and string keys.
 * Negative indices are supported to count from the end (e.g. -1 is the last element).
 *
 * If `$copy` is true, returns a new array with swapped values, leaving original intact.
 * Otherwise, swaps values directly on the passed array.
 *
 * @param array $ar The array of values to change
 * @param int|string $from The first key/index position to swap (default: 0)
 * @param int|string $to The second key/index position to swap (default: 0)
 * @param bool $copy If true, returns a swapped clone of the passed-in array (default: false)
 * @return array The modified array reference.
 *
 * @example
 * ```php
 * $ar = [1, 2, 3, 4];
 * swap($ar, 1, 3);
 * print_r($ar); // [1, 4, 3, 2]
 *
 * $arAssoc = ['a' => 'apple', 'b' => 'banana', 'c' => 'cherry'];
 * $swapped = swap($arAssoc, 'a', 'c', true);
 * print_r($swapped); // ['c' => 'cherry', 'b' => 'banana', 'a' => 'apple']
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function swap( array $ar , int|string $from = 0, int|string $to = 0, bool $copy = false ): array
{
    if ( $copy )
    {
        $ar = unserialize( serialize( $ar ) ) ; // deep copy
    }

    $keys = array_keys( $ar ) ;

    if ( is_int( $from ) && $from < 0 )
    {
        $from = $keys[count($keys) + $from] ?? null;
    }

    if ( is_int( $to ) && $to < 0 )
    {
        $to = $keys[count($keys) + $to] ?? null;
    }

    if ( $from !== null && $to !== null && array_key_exists($from, $ar) && array_key_exists($to, $ar))
    {
        $temp        = $ar[ $from ] ;
        $ar[ $from ] = $ar[ $to   ] ;
        $ar[ $to   ] = $temp;
    }

    return $ar;
}