<?php

namespace oihana\core\strings ;

/**
 * Uppercases the first character of a string (multibyte-safe).
 *
 * Unlike the native {@see ucfirst()}, accented and multibyte first letters are handled
 * correctly. The rest of the string is left untouched.
 *
 * @param string $source The input string.
 *
 * @return string The string with its first character upper-cased.
 *
 * @example
 * ```php
 * use function oihana\core\strings\ucFirst;
 *
 * echo ucFirst( 'hello' ) ; // "Hello"
 * echo ucFirst( 'élan' )  ; // "Élan"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
// PHP function names are case-insensitive: this shadows the builtin \ucfirst() inside the
// oihana\core\strings namespace, where an unqualified ucfirst() resolves here. Use \ucfirst() for the native one.
function ucFirst( string $source ): string
{
    if ( $source === '' )
    {
        return '' ;
    }
    return mb_strtoupper( mb_substr( $source , 0 , 1 , 'UTF-8' ) , 'UTF-8' ) . mb_substr( $source , 1 , null , 'UTF-8' ) ;
}
