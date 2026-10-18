<?php

namespace oihana\core\maths ;

use InvalidArgumentException ;

use function oihana\core\maths\gcd ;

/**
 * Returns the aspect ratio of a width and a height, simplified by their greatest common divisor,
 * e.g. `"16:9"` for 1920 × 1080.
 *
 * The two numbers are divided by {@see gcd()}: 1920 × 1080 and 1280 × 720 both give `"16:9"`,
 * 1280 × 1080 gives `"32:27"`. The separator defaults to a colon and can be changed (`"16/9"`).
 *
 * A width or a height of zero or below has no ratio — and a negative one would be read as its
 * absolute value by `gcd()`, hiding an error upstream: both throw rather than return a string a
 * screen would show without anyone knowing why.
 *
 * To scale a size while keeping its ratio, see {@see aspectFit()}.
 *
 * @param int    $width     The width, greater than 0.
 * @param int    $height    The height, greater than 0.
 * @param string $separator The string written between the two numbers (default `':'`).
 *
 * @return string The simplified ratio, e.g. `"16:9"`.
 *
 * @throws InvalidArgumentException When the width or the height is zero or below.
 *
 * @example
 * ```php
 * use function oihana\core\maths\aspectRatio ;
 *
 * echo aspectRatio( 1920 , 1080 ) ;        // "16:9"
 * echo aspectRatio( 1280 , 1080 ) ;        // "32:27"
 * echo aspectRatio( 1920 , 1080 , '/' ) ;  // "16/9"
 * echo aspectRatio( 1080 , 1080 ) ;        // "1:1"
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function aspectRatio( int $width , int $height , string $separator = ':' ) : string
{
    if ( $width <= 0 )
    {
        throw new InvalidArgumentException( sprintf( 'The width must be greater than 0, %d given.' , $width ) ) ;
    }

    if ( $height <= 0 )
    {
        throw new InvalidArgumentException( sprintf( 'The height must be greater than 0, %d given.' , $height ) ) ;
    }

    $divisor = gcd( $width , $height ) ;

    return intdiv( $width , $divisor ) . $separator . intdiv( $height , $divisor ) ;
}
