<?php

namespace oihana\core\strings ;

/**
 * Checks if a character is a known quote character.
 *
 * @return bool True if the character is a quote, false otherwise.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.0
 *
 * @example
 * ```php
 * isQuote('"');  // true
 * isQuote('`');  // true
 * isQuote('a');  // false
 * isQuote('»');  // true
 * ```
 */
function isQuote( string $char ) :bool
{
    // List of characters considered as quotes
    static $quotes =
    [
        "'", '"', '`',      // single, double, backtick
        '«', '»',           // French quotes
        '“', '”',           // English quotes
        '‘', '’',           // English single quotes / typographic apostrophe
    ];

    return in_array( $char , $quotes , true ) ;
}