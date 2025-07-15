<?php

namespace oihana\core\strings ;

/**
 * Convert the given string to lower-case.
 * @param ?string $source The string expression to format.
 * @return string The lower-case string.
 * @example
 * echo lower("HELLO WORLD"); // Outputs: "hello world"
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
        return mb_strtolower( $source , $encoding );
    }

    return strtolower( $source ) ;
}