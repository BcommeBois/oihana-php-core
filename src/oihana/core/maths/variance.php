<?php

namespace oihana\core\maths ;

use InvalidArgumentException;

/**
 * Computes the variance of a list of numbers.
 *
 * By default the population variance is returned (sum of squared deviations divided
 * by `N`). When `$sample` is `true`, the sample variance is returned instead (divided
 * by `N - 1`, Bessel's correction).
 *
 * @param array<int, int|float> $values A list of numeric values (at least one for population, two for sample).
 * @param bool                  $sample Whether to compute the sample variance (`N - 1`) instead of the population one (`N`).
 *
 * @return float The variance of the values.
 *
 * @throws InvalidArgumentException If the array is empty, or has fewer than two values when `$sample` is `true`.
 *
 * @example
 * ```php
 * use function oihana\core\maths\variance;
 *
 * echo variance( [ 2 , 4 , 4 , 4 , 5 , 5 , 7 , 9 ] )        ; // 4.0  (population)
 * echo variance( [ 2 , 4 , 4 , 4 , 5 , 5 , 7 , 9 ] , true ) ; // ~4.571 (sample)
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function variance( array $values , bool $sample = false ) :float
{
    $count = count( $values ) ;
    if ( $count === 0 || ( $sample && $count < 2 ) )
    {
        throw new InvalidArgumentException( $sample
            ? 'variance(): sample variance requires at least two values.'
            : 'variance(): the array must not be empty.' ) ;
    }

    $mean       = mean( $values ) ;
    $sumSquares = 0.0 ;
    foreach ( $values as $value )
    {
        $delta       = $value - $mean ;
        $sumSquares += $delta * $delta ;
    }

    return $sumSquares / ( $sample ? $count - 1 : $count ) ;
}
