<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use Normalizer;

/**
 * Replaces all occurrences of a substring within a string, with optional
 * Unicode (grapheme-safe) and normalization support.
 *
 * This function handles both simple ASCII strings and full Unicode strings.
 * When `$utf8` is enabled, the function:
 * - Normalizes the source string and the search string using Unicode
 * Normalization Form C (NFC),
 * - Uses grapheme-aware functions (`grapheme_strpos` / `grapheme_stripos`,
 * `grapheme_substr`) to correctly handle composed characters and
 * surrogate pairs.
 *
 * When `$utf8` is disabled, a faster `str_replace` or `str_ireplace` is used.
 *
 * If `$ignoreCase` is true, the search is performed in a
 * case-insensitive, Unicode-aware manner (when `$utf8` is true) or ASCII case-insensitive otherwise.
 *
 * ### Examples
 *
 * Simple replacement:
 * ```php
 * replace('Hello World', 'World', 'Marc');
 * // "Hello Marc"
 * ```
 *
 * Unicode-safe replacement:
 * ```php
 * replace('école', 'é', 'E' , utf8:true );
 * // "Ecole"
 * ```
 *
 * Emoji replacement (surrogate pairs):
 * ```php
 * replace('I ❤️ PHP', '❤️', '💛', false, true);
 * // Returns "I 💛 PHP"
 * ```
 *
 * Case-insensitive replacement:
 * ```php
 * replace('Straße', 'STRASSE', 'Street', ignoreCase: true , utf8: true );
 * ```
 *
 * Null source:
 * ```php
 * replace(null, 'foo', 'bar');
 * // Returns ""
 * ```
 *
 * @param string|null $source
 * The source string to process. If `null`, an empty string is assumed.
 *
 * @param string $from
 * The substring to search for. Must be a valid UTF-8 string.
 *
 * @param string $to
 * The replacement string.
 *
 * @param bool $ignoreCase
 * Whether the search should be performed in a case-insensitive way.
 *
 * @param bool $utf8
 * Whether to enable Unicode-aware, normalization-safe replacement.
 *
 * @return string
 * The resulting string after replacement.
 *
 * @throws InvalidArgumentException
 * Thrown if `$from` or the resulting string is not valid UTF-8 and cannot be normalized when `$utf8` is true.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function replace
(
    ?string $source     ,
    string  $from       ,
    string  $to         ,
    bool    $ignoreCase = false ,
    bool    $utf8       = false
)
: string
{
    $source ??= '' ;

    if ( $source === '' )
    {
        return $source;
    }

    if ( !$utf8 )
    {
        return $ignoreCase ? str_ireplace( $from , $to , $source ) : str_replace( $from , $to , $source ) ;
    }

    if (!normalizer_is_normalized( $source , Normalizer::FORM_C ) )
    {
        $normalized = normalizer_normalize( $source , Normalizer::FORM_C ) ;
        if ( $normalized === false )
        {
            throw new InvalidArgumentException( 'Invalid UTF-8 string in $source.' ) ;
        }
        $source = $normalized ;
    }

    if ( !normalizer_is_normalized( $from , Normalizer::FORM_C ) )
    {
        $normalizedFrom = normalizer_normalize( $from , Normalizer::FORM_C ) ;
        if ( $normalizedFrom === false )
        {
            throw new InvalidArgumentException( 'Invalid UTF-8 string in $from.' ) ;
        }
        $from = $normalizedFrom;
    }

    if ( $from === '' )
    {
        return $source ;
    }

    $tail    = $source ;
    $result  = '' ;
    $indexOf = $ignoreCase ? 'grapheme_stripos' : 'grapheme_strpos' ;

    while ( $tail !== '' && false !== ($i = $indexOf($tail, $from) ) )
    {
        $slice   = grapheme_substr($tail, 0, $i) ;
        $result .= $slice . $to ;
        $tail    = substr( $tail , strlen( $slice ) + strlen($from) ) ;
    }

    $resultString = $result . $tail ;
    if ( !normalizer_is_normalized( $resultString , Normalizer::FORM_C ) )
    {
        $normalizedResult = normalizer_normalize( $resultString , Normalizer::FORM_C ) ;
        if ( $normalizedResult === false )
        {
            throw new InvalidArgumentException( 'Invalid UTF-8 string.' ) ;
        }
        $resultString = $normalizedResult ;
    }

    return $resultString;
}