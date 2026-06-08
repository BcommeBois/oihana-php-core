<?php

namespace oihana\core\strings ;

/**
 * Uppercases the first letter of each word in a string (multibyte-safe).
 *
 * A word starts at any letter that is not preceded by a letter or a digit, so words
 * separated by spaces, punctuation or symbols are all handled. The rest of each word is
 * left untouched.
 *
 * @param string $source The input string.
 *
 * @return string The string with the first letter of each word upper-cased.
 *
 * @example
 * ```php
 * use function oihana\core\strings\ucWords;
 *
 * echo ucWords( 'hello world' )   ; // "Hello World"
 * echo ucWords( 'foo-bar baz' )   ; // "Foo-Bar Baz"
 * echo ucWords( 'éric à paris' )  ; // "Éric À Paris"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
// PHP function names are case-insensitive: this shadows the builtin \ucwords() inside the
// oihana\core\strings namespace, where an unqualified ucwords() resolves here. Use \ucwords() for the native one.
function ucWords( string $source ): string
{
    return preg_replace_callback( '/(?<![\p{L}\p{N}])\p{L}/u' , fn( array $match ): string => mb_strtoupper( $match[ 0 ] , 'UTF-8' ) , $source ) ;
}
