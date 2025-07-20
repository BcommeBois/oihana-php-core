<?php

namespace oihana\core\arrays ;

/**
 * Shuffles the elements of an array in place using the Fisher-Yates algorithm.
 *
 * This function randomizes the order of the elements in the given array.
 * It operates directly on the passed array (by reference) and also returns it.
 * The algorithm used ensures an unbiased shuffle.
 *
 * If the array has 0 or 1 element, it is returned as is without changes.
 *
 * @param array &$ar The array to shuffle (passed by reference).
 *
 * @return array The shuffled array.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\shuffle;
 *
 * $numbers = [1, 2, 3, 4, 5];
 * shuffle($numbers);
 * print_r($numbers); // e.g. [3, 1, 5, 2, 4] - order randomized
 *
 * $empty = [];
 * shuffle($empty);
 * var_dump($empty); // []
 *
 * $single = [42];
 * shuffle($single);
 * var_dump($single); // [42]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function shuffle( array &$ar ): array
{
    $len = count( $ar ) ;
    if ( $len <= 1 )
    {
        return $ar;
    }

    for ( $i = $len - 1 ; $i > 0 ; $i-- )
    {
        $randomIndex = mt_rand(0, $i) ;
        $temp = $ar[$i];
        $ar[ $i ] = $ar[$randomIndex] ;
        $ar[ $randomIndex ] = $temp ;
    }

    return $ar;
}