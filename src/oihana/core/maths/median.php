<?php

namespace oihana\core\maths ;

use InvalidArgumentException;

/**
 * Computes the median (middle value) of a list of numbers.
 *
 * The values are sorted numerically. For an odd number of values the middle one is
 * returned ; for an even number, the average of the two central values is returned.
 *
 * @param array $values A non-empty list of numeric values.
 *
 * @return float The median of the values.
 *
 * @throws InvalidArgumentException If the array is empty.
 *
 * @example
 * ```php
 * use function oihana\core\maths\median;
 *
 * echo median( [ 3 , 1 , 2 ] )     ; // 2.0
 * echo median( [ 4 , 1 , 3 , 2 ] ) ; // 2.5
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function median( array $values ) :float
{
    $count = count( $values ) ;
    if ( $count === 0 )
    {
        throw new InvalidArgumentException( 'median(): the array must not be empty.' ) ;
    }

    $values = array_values( $values ) ;
    sort( $values , SORT_NUMERIC ) ;

    $middle = intdiv( $count , 2 ) ;
    if ( $count % 2 === 1 )
    {
        return (float) $values[ $middle ] ;
    }
    return ( $values[ $middle - 1 ] + $values[ $middle ] ) / 2 ;
}
