<?php

namespace oihana\core\maths ;

/**
 * Scales a dimension pair to a target width or height while preserving the
 * original aspect ratio (the locked-ratio behaviour of an aspect-ratio box).
 *
 * Provide `$targetWidth` to derive the matching height, or `$targetHeight` to
 * derive the matching width. If both are given, `$targetWidth` takes precedence
 * and `$targetHeight` is ignored. If both are null, the original pair is
 * returned. A non-positive original `$width`/`$height` yields an undefined
 * ratio: the provided targets (or the originals) are returned unchanged.
 *
 * @param int      $width        The original (reference) width.
 * @param int      $height       The original (reference) height.
 * @param int|null $targetWidth  The desired width, or null.
 * @param int|null $targetHeight The desired height, or null.
 *
 * @return array{width:int,height:int} The scaled dimensions preserving the ratio.
 *
 * @example
 * ```php
 * use function oihana\core\maths\aspectFit ;
 *
 * aspectFit( 1920 , 1080 , targetWidth: 1280 ) ; // [ 'width' => 1280 , 'height' => 720 ]
 * aspectFit( 1920 , 1080 , targetHeight: 540 ) ; // [ 'width' => 960  , 'height' => 540 ]
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function aspectFit( int $width , int $height , ?int $targetWidth = null , ?int $targetHeight = null ) : array
{
    if ( $width <= 0 || $height <= 0 )
    {
        return [ Dimension::WIDTH => $targetWidth ?? $width , Dimension::HEIGHT => $targetHeight ?? $height ] ;
    }

    if ( $targetWidth !== null )
    {
        return [ Dimension::WIDTH => $targetWidth , Dimension::HEIGHT => (int) round( $targetWidth * $height / $width ) ] ;
    }

    if ( $targetHeight !== null )
    {
        return [ Dimension::WIDTH => (int) round( $targetHeight * $width / $height ) , Dimension::HEIGHT => $targetHeight ] ;
    }

    return [ Dimension::WIDTH => $width , Dimension::HEIGHT => $height ] ;
}
