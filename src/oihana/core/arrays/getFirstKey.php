<?php

namespace oihana\core\arrays ;

/**
 * Returns the first key from the provided list of keys that exists in the given array.
 * If none of the keys exist, the default value is returned.
 *
 * @param array $array   The array to search within.
 * @param array $keys    An ordered list of keys to check in the array.
 * @param mixed $default The value to return if none of the keys exist in the array. Default is null.
 *
 * @return mixed Returns the first matching key if found, otherwise returns the default value.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\getFirstKey;
 *
 * $definition =
 * [
 *     'list'   => [ 1 , 2 , 3 ] ,
 *     'concat' => 'abc' ,
 * ];
 *
 * $key = getFirstKey($definition, ['array', 'list', 'concat'], 'concat');
 * // $key === 'list'
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function getFirstKey( array $array , array $keys , mixed $default = null ) :mixed
{
    foreach ( $keys as $key )
    {
        if ( isset( $array[ $key ] ) )
        {
            return $key ;
        }
    }
    return $default;
}