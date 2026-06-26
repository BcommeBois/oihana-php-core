<?php

namespace oihana\core\maths ;

use InvalidArgumentException;

/**
 * Computes the arithmetic mean (average) of a list of numbers.
 *
 * @param array<int, int|float> $values A non-empty list of numeric values.
 *
 * @return float The arithmetic mean of the values.
 *
 * @throws InvalidArgumentException If the array is empty.
 *
 * @example
 * ```php
 * use function oihana\core\maths\mean;
 *
 * echo mean( [ 1 , 2 , 3 , 4 ] ) ; // 2.5
 * echo mean( [ 2 , 4 , 6 ] )      ; // 4.0
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function mean( array $values ) :float
{
    $count = count( $values ) ;
    if ( $count === 0 )
    {
        throw new InvalidArgumentException( 'mean(): the array must not be empty.' ) ;
    }
    return array_sum( $values ) / $count ;
}
