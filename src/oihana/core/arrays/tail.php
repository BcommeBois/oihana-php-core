<?php

namespace oihana\core\arrays ;

/**
 * Returns the elements of the array without the first in a new array representation.
 *
 * @param array $array The input array
 * @return array A new array without the first element (empty array if input is empty or not an array)
 *
 * @example
 * print_r(tail([2, 3, 4])); // [3, 4]
 */
function tail( array $array ): array
{
    return array_slice( $array , 1 ) ;
}