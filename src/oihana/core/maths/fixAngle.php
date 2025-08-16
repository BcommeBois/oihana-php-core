<?php

namespace oihana\core\maths ;

/**
 * Normalize an angle in degrees to the range [0, 360).
 *
 * If the input is not numeric (NaN, null, etc.), the function will return 0.
 *
 * @param float|int $angle The angle in degrees.
 *
 * @return float The normalized angle between 0 (inclusive) and 360 (exclusive).
 *
 * @example
 * ```php
 * echo fixAngle(370);   // 10
 * echo fixAngle(-90);   // 270
 * echo fixAngle(720.5); // 0.5
 * echo fixAngle(NAN);   // 0
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function fixAngle( float|int $angle ): float
{
    if ( is_nan( ( float ) $angle ) )
    {
        $angle = 0.0;
    }

    $angle = fmod( $angle , 360.0 ) ;

    return ($angle < 0) ? $angle + 360.0 : $angle ;
}