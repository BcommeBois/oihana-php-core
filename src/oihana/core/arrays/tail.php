<?php

namespace oihana\core\arrays ;

/**
 * Returns a new array containing all elements except the first one.
 *
 * If the input array is empty, returns an empty array.
 *
 * @param array $array The input array.
 *
 * @return array A new array without the first element.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\tail;
 *
 * $arr = [2, 3, 4];
 * print_r(tail($arr)); // Outputs: [3, 4]
 *
 * $empty = [];
 * print_r(tail($empty)); // Outputs: []
 *
 * $single = [10];
 * print_r(tail($single)); // Outputs: []
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function tail( array $array ): array
{
    return array_slice( $array , 1 ) ;
}