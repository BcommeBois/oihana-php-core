<?php

namespace oihana\core\arrays ;

/**
 * Swaps two indexed values in a specific array representation.
 *
 * @param array $ar The array of values to change
 * @param int|string $from The first key/index position to swap (default: 0)
 * @param int|string $to The second key/index position to swap (default: 0)
 * @param bool $clone If true, returns a swapped clone of the passed-in array (default: false)
 * @return array The modified array reference.
 *
 * @example
 * ```php
 * $ar = [1, 2, 3, 4];
 * print_r($ar); // [1, 2, 3, 4]
 * $result = swap($ar, 1, 3);
 * print_r($ar); // [1, 4, 3, 2]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function swap( array $ar , int|string $from = 0, int|string $to = 0, bool $clone = false ): array
{
    if ( $clone )
    {
        $ar = unserialize( serialize( $ar ) ) ; // deep copy
    }

    if ( is_int($from) && $from < 0 )
    {
        $from = count($ar) + $from ;
    }

    if ( is_int( $to ) && $to < 0 )
    {
        $to = count($ar) + $to;
    }

    if ( array_key_exists( $from , $ar ) && array_key_exists( $to , $ar ) )
    {
        $temp      = $ar[ $from ] ;
        $ar[$from] = $ar[ $to   ] ;
        $ar[$to]   = $temp ;
    }

    return $ar;
}