<?php

namespace oihana\core\strings ;

/**
 * Masks the middle part of a string, keeping a few grapheme clusters visible at each end.
 *
 * Useful for hiding sensitive data (emails, tokens, card numbers). The first
 * `$visibleStart` and last `$visibleEnd` grapheme clusters are kept, every grapheme
 * in between is replaced by `$char`. When the visible regions cover (or overlap) the
 * whole string, there is nothing to hide and the original string is returned unchanged.
 *
 * @param string $source       The input string.
 * @param int    $visibleStart The number of leading grapheme clusters to keep visible. Default `0`.
 * @param int    $visibleEnd   The number of trailing grapheme clusters to keep visible. Default `4`.
 * @param string $char         The masking character. Default `*`.
 *
 * @return string The masked string.
 *
 * @example
 * ```php
 * use function oihana\core\strings\mask;
 *
 * echo mask( '4242424242424242' )            ; // "************4242"
 * echo mask( 'secret' , 1 , 1 )              ; // "s****t"
 * echo mask( 'john@doe.com' , 2 , 4 , '•' )  ; // "jo••••••.com"
 * echo mask( 'abc' , 2 , 2 )                 ; // "abc" (regions overlap, returned as-is)
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function mask( string $source , int $visibleStart = 0 , int $visibleEnd = 4 , string $char = '*' ) :string
{
    $length = grapheme_strlen( $source ) ;

    $visibleStart = max( 0 , $visibleStart ) ;
    $visibleEnd   = max( 0 , $visibleEnd ) ;

    if ( $visibleStart + $visibleEnd >= $length )
    {
        return $source ;
    }

    $start  = $visibleStart > 0 ? grapheme_substr( $source , 0 , $visibleStart ) : '' ;
    $end    = $visibleEnd   > 0 ? grapheme_substr( $source , - $visibleEnd ) : '' ;
    $masked = str_repeat( $char , $length - $visibleStart - $visibleEnd ) ;

    return $start . $masked . $end ;
}
