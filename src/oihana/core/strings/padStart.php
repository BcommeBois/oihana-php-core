<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Pads a UTF-8 string on the left (start) to a certain length using a specified padding string.
 *
 * If the source string is null, it is treated as an empty string.
 * The padding uses grapheme clusters to correctly handle multibyte characters.
 *
 * @param ?string $source The input string or null.
 * @param int     $size   Desired length after padding (in grapheme clusters).
 * @param string  $pad    The string to use for padding (cannot be empty).
 *
 * @return string The left-padded string.
 *
 * @throws InvalidArgumentException If the padding string is empty or invalid UTF-8.
 *
 * @example
 * ```php
 * use function oihana\core\strings\padStart;
 *
 * echo padStart('hello', 10, '☺');  // Outputs: "☺☺☺☺☺hello"
 * echo padStart(null, 5, '*');      // Outputs: "*****"
 * ```
 *
 * @package oihana\core\strings
 * @author Marc Alcaraz (ekameleon)
 * @since 1.0.0
 */
function padStart( ?string $source , int $size , string $pad ) :string
{
    return pad( $source , $size , $pad , STR_PAD_LEFT ) ;
}