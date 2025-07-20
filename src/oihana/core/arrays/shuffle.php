<?php

namespace oihana\core\arrays ;

/**
 * Shuffles an array using the Fisher-Yates algorithm.
 * @param array &$ar The array to shuffle (passed by reference).
 * @return array The shuffled array, or null if input is not an array.
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