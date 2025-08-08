<?php

namespace oihana\core\strings ;

/**
 * Extracts a substring from a UTF-8 encoded string using grapheme clusters,
 * which ensures multi-byte characters (like emojis, accented letters) are not broken.
 * If the source is null, it returns an empty string.
 *
 * @param ?string $source The input string or null.
 * @param int     $start  The start position (0-based). Negative values count from the end.
 * @param ?int    $length The length of the substring. Defaults to max int (whole string from $start).
 *
 * @return string The extracted substring or empty string if $source is null.
 *
 * @example
 * ```php
 * use function oihana\core\strings\slice;
 *
 * echo slice ( "Hello World", 6 ) ; // Outputs: "World"
 *
 * echo slice ( "👩‍👩‍👧‍👧 family", 0, 5 ) ; // Outputs: "👩‍👩‍👧‍👧"
 *
 * echo slice ( null ) ; // Outputs: ""
 *
 * echo slice ( "Hello" , -3 , 2 ) ; // Outputs: "ll"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function slice( ?string $source , int $start = 0 , ?int $length = null ) :string
{
    if ( $source === null )
    {
        return '' ;
    }

    $length ??= grapheme_strlen( $source ) ;

    if ( function_exists('grapheme_substr' ) )
    {
        $result = grapheme_substr( $source , $start , $length ) ;
    }
    else
    {
        $result = mb_substr( $source , $start , $length , 'UTF-8' ) ;
    }

    return $result === false ? '' : $result ;
}