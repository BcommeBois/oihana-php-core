<?php

namespace oihana\core\strings ;

/**
 * Converts a string to camelCase format.
 *
 * This function transforms the input string by replacing specified separator characters
 * with spaces, capitalizing each word, removing spaces, and then lowercasing the first character.
 *
 * @param ?string            $source      The input string to convert to camelCase. If null or empty, returns an empty string.
 * @param array<int, string> $separators  An array of separator characters to be replaced by spaces before conversion. Defaults to ["_", "-", "/"].
 *
 * @return string The camelCase representation of the input string.
 *
 * @example
 * ```php
 * echo camel('hello_world');        // Outputs: helloWorld
 * echo camel('foo-bar_baz');        // Outputs: fooBarBaz
 * echo camel('user/name');          // Outputs: userName
 * echo camel('alreadyCamelCase');   // Outputs: alreadyCamelCase
 * echo camel(null);                 // Outputs: (empty string)
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function camel( ?string $source , array $separators = [ "_" , "-" , "/" ] ): string
{
    if ( !is_string( $source ) || $source === '' )
    {
        return '' ;
    }
    $words  = preg_split( '/\s+/u' , str_replace( $separators , ' ' , $source ) , -1 , PREG_SPLIT_NO_EMPTY ) ;
    $result = implode( '' , array_map( ucFirst( ... ) , $words ) ) ;
    return mb_strtolower( mb_substr( $result , 0 , 1 , 'UTF-8' ) , 'UTF-8' ) . mb_substr( $result , 1 , null , 'UTF-8' ) ;
}