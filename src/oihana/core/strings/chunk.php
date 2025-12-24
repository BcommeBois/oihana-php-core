<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Splits a string into groups of length `$size`, separated by `$separator`.
 *
 * This function supports both simple ASCII strings and full Unicode strings.
 *
 * When `$utf8` is `true`, the function uses **grapheme-safe operations** (`grapheme_strlen` / `grapheme_substr`)
 * to correctly handle composed characters, accented letters, surrogate pairs, and emojis.
 *
 * When `$utf8` is `false`, a faster byte-based method is used (`strlen` / `substr`), which works well for ASCII.
 *
 * ### Examples
 *
 * ASCII examples:
 * ```php
 * chunk('565448', 2);          // "56 54 48"
 * chunk('1525', 2, '-');       // "15-25"
 * chunk('25', 2);              // "25"
 * chunk('123456789', 3, ':');  // "123:456:789"
 * ```
 *
 * Unicode / accents:
 * ```php
 * chunk('éàüö', 2, ' ', true); // "éà üö"
 * ```
 *
 * Emoji:
 * ```php
 * chunk('💛❤️💛', 2, ' ', true); // "💛 ❤️ 💛"
 * ```
 *
 * Null or empty source:
 * ```php
 * chunk(null, 2); // ""
 * chunk('', 3);   // ""
 * ```
 *
 * @param string|null $source
 * The source string to split. If `null`, it is treated as an empty string.
 *
 * @param int $size
 * The length of each group. Must be greater than 0.
 *
 * @param string $separator
 * The separator string to insert between groups. Default is a single space.
 *
 * @param bool $utf8
 * Whether to use Unicode-aware grapheme-safe splitting. Default is `false`.
 *
 * @return string
 * The resulting string after grouping.
 *
 * @throws InvalidArgumentException
 * Thrown if `$size` is less than or equal to 0.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function chunk
(
    ?string $source ,
    int     $size   ,
    string  $separator = ' ' ,
    bool    $utf8      = false
)
: string
{
    $source ??= '' ;

    if ( $source === '' )
    {
        return $source ;
    }

    if ( $size <= 0 )
    {
        throw new InvalidArgumentException('Group length $n must be greater than 0.') ;
    }

    // ASCII fast path
    if ( !$utf8 )
    {
        $length = strlen( $source ) ;

        if ( $length <= $size )
        {
            return $source ;
        }

        $result = '' ;

        for ( $i = 0 ; $i < $length ; $i += $size )
        {
            $result .= substr( $source , $i , $size ) . $separator ;
        }

        return rtrim( $result , $separator ) ;
    }

    // Unicode / grapheme safe

    $length = grapheme_strlen( $source ) ;

    if ( $length <= $size )
    {
        return $source ;
    }

    $result = '' ;

    for ( $i = 0 ; $i < $length ; $i += $size )
    {
        $result .= grapheme_substr( $source , $i , $size ) . $separator;
    }

    // Remove the last separator
    return rtrim( $result , $separator ) ;
}
