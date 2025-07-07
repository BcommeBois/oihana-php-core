<?php

namespace oihana\core\strings ;

/**
 * Returns the camel case representation of the specific expression.
 * @param ?string $source The expression to camelcase.
 * @param array $separators The enumeration of separators to replace in the word expression.
 * @return string The new camelCase representation.
 */
function camel( ?string $source , array $separators = [ "_" , "-" , "/" ] ): string
{
    if ( !is_string( $source ) || $source === '' )
    {
        return '' ;
    }
    return lcfirst( str_replace(' ' , '' , ucwords( str_replace( $separators, ' ' , $source ) ) ) ) ;
}