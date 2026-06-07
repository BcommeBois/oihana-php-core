<?php

namespace oihana\core\maths ;

use InvalidArgumentException;

/**
 * Computes the factorial `n!` of a non-negative integer.
 *
 * `factorial(0)` is `1`. The argument is capped at `20` because `21!` exceeds
 * `PHP_INT_MAX` and could no longer be represented exactly as an `int`.
 *
 * @param int $n A non-negative integer in the range `[0, 20]`.
 *
 * @return int The factorial of `$n`.
 *
 * @throws InvalidArgumentException If `$n` is negative or greater than `20`.
 *
 * @example
 * ```php
 * use function oihana\core\maths\factorial;
 *
 * echo factorial( 0 ) ; // 1
 * echo factorial( 5 ) ; // 120
 * echo factorial( 10 ) ; // 3628800
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function factorial( int $n ) :int
{
    if ( $n < 0 || $n > 20 )
    {
        throw new InvalidArgumentException( 'factorial(): $n must be an integer between 0 and 20 inclusive.' ) ;
    }

    $result = 1 ;
    for ( $i = 2 ; $i <= $n ; $i++ )
    {
        $result *= $i ;
    }
    return $result ;
}
