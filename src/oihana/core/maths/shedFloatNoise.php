<?php

namespace oihana\core\maths ;

/**
 * Sheds the binary noise a computed figure carries, and rounds no business figure.
 *
 * Adding floats leaves an error that grows with the figure : a sum of two hundred thousand comes back
 * as `202799.4000000001`, a sum of forty five thousand as `45292.249999999985`. A fixed count of
 * decimals — ten, say — cleans the second and leaves the first, because the noise of a double sits at
 * a **relative** distance of the value, not at a fixed decimal.
 *
 * 🔑 **The scale follows the size of the value.** The figure is rounded to a count of **significant
 * digits** : twelve by default, where a double holds fifteen to seventeen — the last ones are left to
 * the noise, and every decimal a source records survives, a quantity of `13039.42858`, a weight of
 * `55999.81828`, a volume of `0.0073`.
 *
 * ⚠️ **Not a rounding.** Two decimals on an amount or five on a quantity are business rules, written
 * where the figure is shown or stored ({@see roundValue()}). This function only removes what an
 * addition of floats left behind : a figure that was never computed needs none of it.
 *
 * Zero, an infinite figure and a NaN are answered as they are.
 *
 * @param int|float $value  The computed figure.
 * @param int       $digits How many significant digits to keep. Default `12`.
 *
 * @return float The figure, rid of its noise.
 *
 * @example
 * ```php
 * use function oihana\core\maths\shedFloatNoise;
 *
 * echo shedFloatNoise( 0.1 + 0.2 )          ; // 0.3
 * echo shedFloatNoise( 202799.4000000001 )  ; // 202799.4
 * echo shedFloatNoise( 45292.249999999985 ) ; // 45292.25
 * echo shedFloatNoise( 13039.42858 )        ; // 13039.42858 — a real decimal survives
 * echo shedFloatNoise( 0.0073 )             ; // 0.0073
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function shedFloatNoise( int|float $value , int $digits = 12 ) : float
{
    $value = (float) $value ;

    if ( $value === 0.0 || !is_finite( $value ) )
    {
        return $value ;
    }

    return round( $value , max( 0 , $digits - 1 - (int) floor( log10( abs( $value ) ) ) ) ) ;
}
