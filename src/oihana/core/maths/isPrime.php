<?php

namespace oihana\core\maths ;

/**
 * Tells whether an integer is a prime number.
 *
 * Any integer lower than `2` is not prime. The test uses `6k ± 1` trial division up
 * to `√n`.
 *
 * @param int $n The integer to test.
 *
 * @return bool `true` if `$n` is prime, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\maths\isPrime;
 *
 * isPrime( 2 )  ; // true
 * isPrime( 17 ) ; // true
 * isPrime( 25 ) ; // false
 * isPrime( 1 )  ; // false
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function isPrime( int $n ) :bool
{
    if ( $n < 2 )
    {
        return false ;
    }
    if ( $n % 2 === 0 )
    {
        return $n === 2 ;
    }
    if ( $n % 3 === 0 )
    {
        return $n === 3 ;
    }
    for ( $i = 5 ; $i * $i <= $n ; $i += 6 )
    {
        if ( $n % $i === 0 || $n % ( $i + 2 ) === 0 )
        {
            return false ;
        }
    }
    return true ;
}
