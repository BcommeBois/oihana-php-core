<?php

namespace oihana\core\strings ;

/**
 * Capitalizes a string: uppercases the first character and lowercases the rest (multibyte-safe).
 *
 * @param string $source The input string.
 *
 * @return string The capitalized string.
 *
 * @example
 * ```php
 * use function oihana\core\strings\capitalize;
 *
 * echo capitalize( 'hELLO' )       ; // "Hello"
 * echo capitalize( 'hello world' ) ; // "Hello world"
 * echo capitalize( 'ÉLAN' )        ; // "Élan"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function capitalize( string $source ): string
{
    if ( $source === '' )
    {
        return '' ;
    }
    return mb_strtoupper( mb_substr( $source , 0 , 1 , 'UTF-8' ) , 'UTF-8' ) . mb_strtolower( mb_substr( $source , 1 , null , 'UTF-8' ) , 'UTF-8' ) ;
}
