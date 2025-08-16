<?php

namespace oihana\core\maths ;

use InvalidArgumentException;

/**
 * Calculate the Greatest Common Divisor (GCD) of two integers using
 * the Euclidean algorithm.
 *
 * This function returns the absolute value of the GCD. If both numbers
 * are zero, it either returns 0 or throws an exception depending on
 * the `$throwable` parameter.
 *
 * @param int  $a         The first integer.
 * @param int  $b         The second integer.
 * @param bool $throwable If true, throws an exception when both numbers are zero.
 *
 * @return int The greatest common divisor of $a and $b.
 *
 * @example
 * ```php
 * echo gcd(48, 18); // Outputs: 6
 * echo gcd(-48, 18); // Outputs: 6
 * echo gcd(0, 5);    // Outputs: 5
 * echo gcd(0, 0);    // Outputs: 0
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function gcd( int $a, int $b , bool $throwable = false ) :int
{
    $a = abs( $a ) ;
    $b = abs( $b ) ;

    if ($a === 0 && $b === 0)
    {
        if ( $throwable )
        {
            throw new InvalidArgumentException("gcd(0,0) is undefined." ) ;
        }
        return 0 ;
    }

    while ( $b !== 0 )
    {
        [ $a , $b ] = [ $b , $a % $b ] ;
    }

    return $a ;
}