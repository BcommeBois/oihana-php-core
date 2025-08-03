<?php

namespace oihana\core\strings ;

/**
 * Formats a string value with proper escaping and wrapping using the specified quote style.
 *
 * This function escapes necessary characters and wraps the string with either single or double quotes,
 * depending on the provided style. Double-quoted strings will have common control characters escaped as well.
 *
 * @param string $value The raw string to quote and escape.
 * @param string $quoteStyle The style of quoting to use: 'single' or 'double'. Default is 'single'.
 * @param bool   $compact
 *
 * @return string The quoted and escaped string.
 *
 * @example
 * ```php
 * echo formatQuotedString("Hello world");                    // 'Hello world'
 * echo formatQuotedString("Line\nBreak", 'double');         // "Line\nBreak"
 * echo formatQuotedString("She said: 'hi'");                // 'She said: \'hi\''
 * echo formatQuotedString("Use \\ backslash", 'double');    // "Use \\ backslash"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function formatQuotedString( string $value , string $quoteStyle = 'single' , bool $compact = false ) :string
{
    $quote   = $quoteStyle === 'double' ? '"' : "'" ;
    $escaped = addcslashes( $value , $quote . '\\' ) ;

    if ( $quote === '"' || $compact )
    {
        $escaped = str_replace
        (
            [ "\n", "\r", "\t", "\v", "\e", "\f" ],
            [ '\\n', '\\r', '\\t', '\\v', '\\e', '\\f' ],
            $escaped
        );
    }

    return $quote . $escaped . $quote ;
}