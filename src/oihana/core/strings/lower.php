<?php

namespace oihana\core\strings ;

/**
 * Converts a string to lowercase using multibyte-safe methods when possible.
 *
 * This function ensures proper transformation of characters beyond ASCII,
 * such as accented letters (e.g., 'É' → 'é') or other UTF-8 characters.
 *
 * If the string is empty or null, an empty string is returned.
 *
 * @param string|null $source The string to convert to lowercase.
 * @return string The lowercase version of the string.
 *
 * @example
 * ```php
 * echo lower("HELLO WORLD");
 * // Output: "hello world"
 *
 * echo lower("École Française");
 * // Output: "école française"
 *
 * echo lower(null);
 * // Output: ""
 *
 * echo lower("123-ABC");
 * // Output: "123-abc"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function lower( ?string $source ) : string
{
    if( !is_string( $source ) || $source === '' )
    {
        return '' ;
    }

    $encoding = mb_detect_encoding( $source , null , true ) ;
    if ( $encoding !== false )
    {
        return mb_strtolower( $source , $encoding ) ;
    }

    return strtolower( $source ) ;
}