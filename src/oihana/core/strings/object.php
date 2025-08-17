<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Generate a JavaScript-like object string, e.g., `{name:'Eka',age:48}`.
 *
 * This function accepts:
 * - an array of key-value pairs `[ ['key1','value1'], ['key2','value2'] ]`,
 * - a preformatted string `"key1:'value1',key2:'value2'"`,
 * - or null (returns empty braces).
 *
 * It wraps the content in braces `{}`. Optionally, spaces can be added
 * around braces and after commas via the `$spacify` parameter.
 *
 * @param string|array|null $keyValues Array of key-value pairs, string, or null.
 * @param bool $spacify Whether to add spaces around braces and after commas.
 *
 * @return string JavaScript-like object expression.
 *
 * @throws InvalidArgumentException If the array structure is invalid.
 *
 * @example
 * ```php
 * echo object([['name', "'Eka'"], ['age', 47]]);
 * // Outputs: "{name:'Eka',age:47}"
 *
 * echo object([['name', "'Eka'"], ['age', 47]], true);
 * // Outputs: "{ name:'Eka', age:47 }"
 *
 * echo object("foo:'bar'");
 * // Outputs: "{foo:'bar'}"
 *
 * echo object(null, true);
 * // Outputs: "{ }"
 * ```
 *
 * @package oihana\core\strings
 * @since 1.0.0
 * @author Marc Alcaraz
 */
function object( null|string|array $keyValues = [] , bool $spacify = false ):string
{
    $space = $spacify ? ' ' : '' ;

    if( is_array( $keyValues ) )
    {
        $properties = array_map( function ( $item )
        {
            if ( !is_array( $item ) || count($item) !== 2 )
            {
                throw new InvalidArgumentException('Each element must be a [key, value] pair.') ;
            }
            [ $key , $value ] = $item ;
            return keyValue( $key , $value ) ;
        }
        , $keyValues ) ;

        $keyValues = implode( ',' . $space , $properties ) ;
    }
    elseif( is_null( $keyValues ) )
    {
        $keyValues = '' ;
    }

    return betweenBraces( $keyValues == '' ? '' : ( $space . $keyValues . $space ) ) ;
}