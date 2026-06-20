<?php

namespace oihana\core\maths ;

/**
 * Defines the canonical keys of a two-dimensional size pair.
 *
 * Using these constants instead of the raw `'width'` / `'height'` magic strings
 * keeps producers and consumers of a dimension pair in sync — for instance the
 * `array{width:int,height:int}` returned by {@see aspectFit()}.
 *
 * @example
 * ```php
 * use oihana\core\maths\Dimension;
 * use function oihana\core\maths\aspectFit;
 *
 * $size = aspectFit( 1920 , 1080 , targetWidth: 1280 ) ;
 *
 * echo $size[ Dimension::WIDTH ]  ; // 1280
 * echo $size[ Dimension::HEIGHT ] ; // 720
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
class Dimension
{
    /**
     * The horizontal extent (width) key.
     * Type: string
     */
    public const string WIDTH = 'width' ;

    /**
     * The vertical extent (height) key.
     * Type: string
     */
    public const string HEIGHT = 'height' ;

    /**
     * Returns the list of valid dimension keys.
     *
     * @return string[]
     */
    public static function all() : array
    {
        return [
            self::WIDTH ,
            self::HEIGHT ,
        ] ;
    }

    /**
     * Checks if a given value is a valid dimension key.
     *
     * @param string $value
     * @return bool
     */
    public static function isValid( string $value ) : bool
    {
        return in_array( $value , self::all() , true ) ;
    }
}
