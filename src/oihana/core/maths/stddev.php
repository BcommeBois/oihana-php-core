<?php

namespace oihana\core\maths ;

use InvalidArgumentException;

/**
 * Computes the standard deviation of a list of numbers.
 *
 * The standard deviation is the square root of the {@see variance()}. By default the
 * population standard deviation is returned ; when `$sample` is `true`, the sample
 * standard deviation is returned (Bessel's correction, `N - 1`).
 *
 * @param array $values A list of numeric values (at least one for population, two for sample).
 * @param bool  $sample Whether to compute the sample standard deviation (`N - 1`) instead of the population one (`N`).
 *
 * @return float The standard deviation of the values.
 *
 * @throws InvalidArgumentException If the array is empty, or has fewer than two values when `$sample` is `true`.
 *
 * @example
 * ```php
 * use function oihana\core\maths\stddev;
 *
 * echo stddev( [ 2 , 4 , 4 , 4 , 5 , 5 , 7 , 9 ] )        ; // 2.0   (population)
 * echo stddev( [ 2 , 4 , 4 , 4 , 5 , 5 , 7 , 9 ] , true ) ; // ~2.138 (sample)
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function stddev( array $values , bool $sample = false ) :float
{
    return sqrt( variance( $values , $sample ) ) ;
}
