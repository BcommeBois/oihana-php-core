<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Generate a JavaScript-like object string.
 *
 * This function converts input into a string representing a JavaScript object,
 * e.g., `{name:'Eka',age:48}`. Supported input types:
 *
 * 1. **Array of key-value pairs**: `[ ['key1','value1'], ['key2','value2'] ]`
 * 2. **Associative array**: `[ 'key1' => 'value1', 'key2' => 'value2' ]`
 * 3. **Preformatted string**: `"key1:'value1',key2:'value2'"`
 * 4. **Null** → returns empty braces `{}`.
 *
 * Each array element can be:
 * - a sub-array with exactly two elements `[key, value]`,
 * - an associative key-value pair,
 * - or a string already formatted as `key:value`.
 *
 * Optionally, spaces can be added around braces and after commas with `$useSpace`.
 *
 * @param array|string|null $keyValues $keyValues Array of pairs, associative array, string, or null
 * @param bool              $useSpace  Add spaces around braces and after commas
 *
 * @return string JavaScript-like object string
 *
 * @throws InvalidArgumentException If an array element is invalid or the type is unsupported
 *
 * @example
 * ```php
 * echo object([['name', "'Eka'"], ['age', 47]]);
 * // Outputs: "{name:'Eka',age:47}"
 *
 * echo object([['name', "'Eka'"], ['age', 47]], true);
 * // Outputs: "{ name:'Eka', age:47 }"
 *
 * echo object(['name' => "'Eka'", 'age' => 47]);
 * // Outputs: "{name:'Eka',age:47}"
 *
 * echo object("foo:'bar',baz:42");
 * // Outputs: "{foo:'bar',baz:42}"
 *
 * echo object(null, true);
 * // Outputs: "{}"
 * ```
 *
 * @package oihana\core\strings
 * @since 1.0.0
 * @author Marc Alcaraz
 */
function object( null|string|array $keyValues = [] , bool $useSpace = false ): string
{
    $space = $useSpace ? ' ' : '' ;

    $content = match (true)
    {
        is_null   ( $keyValues ) => '',
        is_string ( $keyValues ) => trim( $keyValues ) ,
        is_array  ( $keyValues ) => implode
        (
            separator : ',' . $space,
            array     : array_map( static function ( $value , $key )
            {
                $key = preg_match('/^\w+$/', $key) ? $key : "'$key'";

                if ( is_string( $key ) )
                {
                    return keyValue( $key , $value ) ;
                }

                if ( is_string( $value ) )
                {
                    return trim( $value ) ;
                }

                if ( is_array( $value ) && count( $value ) === 2 )
                {
                    return keyValue( $value[0] , $value[1] ) ;
                }

                throw new InvalidArgumentException
                (
                    "Invalid array item: must be [key,value], associative, or string"
                );
            } , $keyValues , array_keys( $keyValues ) )
        ),

        default => throw new InvalidArgumentException
        (
            'Invalid $keyValues type, must be array, string, or null'
        ),
    };

    return betweenBraces($content === '' ? '' : $space . $content . $space);
}