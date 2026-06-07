<?php

namespace oihana\core\strings ;

/**
 * Truncates a string to a maximum number of grapheme clusters, appending an ellipsis.
 *
 * The length is counted in grapheme clusters, so multibyte characters and combined
 * emojis are never split. When the source is longer than `$length`, it is cut to the
 * first `$length` graphemes and `$ellipsis` is appended (the ellipsis is *not* counted
 * in `$length`). Shorter or equal strings are returned unchanged.
 *
 * @param string $source   The input string.
 * @param int    $length   The maximum number of grapheme clusters to keep.
 * @param string $ellipsis The string appended when truncation occurs. Default `…`.
 *
 * @return string The truncated string, or the original when no truncation is needed.
 *
 * @example
 * ```php
 * use function oihana\core\strings\truncate;
 *
 * echo truncate( 'The quick brown fox' , 9 ) ; // "The quick…"
 * echo truncate( 'Hello' , 10 )              ; // "Hello"
 * echo truncate( 'Café société' , 4 , '...' ); // "Café..."
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function truncate( string $source , int $length , string $ellipsis = '…' ) :string
{
    if ( grapheme_strlen( $source ) <= $length )
    {
        return $source ;
    }
    return grapheme_substr( $source , 0 , max( 0 , $length ) ) . $ellipsis ;
}
