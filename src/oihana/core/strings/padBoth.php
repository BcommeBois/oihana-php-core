<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Pads a UTF-8 string on both sides (left and right) to a certain length using a specified padding string.
 *
 * If the source string is null, it is treated as an empty string.
 * The padding uses grapheme clusters to correctly handle multibyte characters.
 *
 * @param ?string $source The input string or null.
 * @param int     $size   Desired length after padding (in grapheme clusters).
 * @param string  $pad    The string to use for padding (cannot be empty).
 *
 * @return string The string padded on both sides.
 *
 * @throws InvalidArgumentException If the padding string is empty or invalid UTF-8.
 *
 * @example
 * ```php
 * use function oihana\core\strings\padBoth;
 *
 * echo padBoth('hello', 11, 'ab');    // Outputs: "abhelloabab"
 * echo padBoth(null, 6, '*');         // Outputs: "******"
 * echo padBoth('test', 10, '☺');      // Outputs: "☺☺☺test☺☺☺"
 * echo padBoth('emoji', 9, '🚀');     // Outputs: "🚀🚀emoji🚀"
 * echo padBoth('pad', 3, '-');        // Outputs: "pad" (size <= string length, no padding)
 * ```
 *
 * @package oihana\core\strings
 * @author Marc Alcaraz (ekameleon)
 * @since 1.0.0
 */
function padBoth( ?string $source , int $size , string $pad ) :string
{
    return pad( $source , $size , $pad , STR_PAD_BOTH ) ;
}