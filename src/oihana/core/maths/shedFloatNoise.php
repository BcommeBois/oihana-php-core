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
 * 🚨 **A sum whose terms cancel needs the scale of its terms.** The noise an addition leaves is
 * relative to the size of the figures **added**, not to the size of the result. When they nearly
 * cancel, the result is tiny and twelve of its own digits are the noise itself : `1000.01 - 1000.00`
 * holds `0.009999999999990905`. Given the size of the largest term as `$scale`, the digits are
 * reckoned on it — twelve at the scale of a thousand are eight decimals — and the figure comes back as
 * `0.01`. The larger of the value and the scale is the one used, so a scale never keeps more digits
 * than the value alone would.
 *
 * The result is never a negative zero : a dust below zero shed to nothing is `0.0`, not `-0.0`.
 * An infinite figure and a NaN are answered as they are.
 *
 * @param int|float      $value  The computed figure.
 * @param int            $digits How many significant digits to keep. Default `12`.
 * @param int|float|null $scale  The size the digits are reckoned on — the largest term of a sum.
 *                               Default `null` : the size of the value itself.
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
 *
 * // Terms that cancel : the result alone is too small to say where the noise starts.
 * echo shedFloatNoise( 1000.01 - 1000.00 )                   ; // 0.00999999999999
 * echo shedFloatNoise( 1000.01 - 1000.00 , scale: 1000.01 )  ; // 0.01
 * echo shedFloatNoise( 1000.00 - 999.99 - 0.01 , scale: 1000 ) ; // 0
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function shedFloatNoise( int|float $value , int $digits = 12 , int|float|null $scale = null ) : float
{
    $value = (float) $value ;

    if ( !is_finite( $value ) )
    {
        return $value ;
    }

    if ( $value === 0.0 )
    {
        return 0.0 ;
    }

    $size = abs( $value ) ;

    if ( $scale !== null && is_finite( (float) $scale ) )
    {
        $size = max( $size , abs( (float) $scale ) ) ;
    }

    // Adding a positive zero turns a negative zero into a positive one, and leaves
    // any other figure as it is.

    return round( $value , max( 0 , $digits - 1 - (int) floor( log10( $size ) ) ) ) + 0.0 ;
}
