<?php

namespace oihana\core\numbers ;

use InvalidArgumentException;

/**
 * Re-maps a value from one range to another.
 *
 * Linearly transforms `$value` from the input range `[$inMin, $inMax]` to the
 * output range `[$outMin, $outMax]` using
 * `$outMin + ( $value - $inMin ) * ( $outMax - $outMin ) / ( $inMax - $inMin )`.
 *
 * The result is not clamped: values outside the input range map outside the output range.
 *
 * @param float $value  The value to re-map.
 * @param float $inMin  The lower bound of the input range.
 * @param float $inMax  The upper bound of the input range.
 * @param float $outMin The lower bound of the output range.
 * @param float $outMax The upper bound of the output range.
 *
 * @return float The value re-mapped into the output range.
 *
 * @throws InvalidArgumentException If the input range is degenerate (`$inMin === $inMax`).
 *
 * @example
 * ```php
 * use function oihana\core\numbers\mapRange;
 *
 * mapRange( 5.0  , 0.0 , 10.0 , 0.0 , 100.0 ) ; // 50.0
 * mapRange( 0.0  , 0.0 , 10.0 , 0.0 , 100.0 ) ; // 0.0
 * mapRange( 10.0 , 0.0 , 10.0 , 0.0 , 100.0 ) ; // 100.0
 * mapRange( 0.0  , -1.0 , 1.0 , 0.0 , 255.0 ) ; // 127.5
 * mapRange( 15.0 , 0.0 , 10.0 , 0.0 , 100.0 ) ; // 150.0 (outside output range)
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function mapRange( float $value , float $inMin , float $inMax , float $outMin , float $outMax ) :float
{
    if ( $inMin === $inMax )
    {
        throw new InvalidArgumentException( 'mapRange() failed: the input range is degenerate ($inMin === $inMax).' ) ;
    }
    return $outMin + ( $value - $inMin ) * ( $outMax - $outMin ) / ( $inMax - $inMin ) ;
}
