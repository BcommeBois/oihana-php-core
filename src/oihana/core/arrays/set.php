<?php

namespace oihana\core\arrays ;

use InvalidArgumentException;

/**
 * Sets a value in an associative array using a key path.
 * If no key is given to the method, the entire array will be replaced.
 *
 * @param array<array-key, mixed> $array  The array to modify (by reference).
 * @param string|null $key       The key path as a string. Can be null, in which case the function replaces the entire array — `$value` must then be an array.
 * @param mixed       $value     The value to set.
 * @param non-empty-string $separator The separator used to split the key into segments. Defaults to a dot ('.').
 * @param bool        $copy      If true, returns a modified copy instead of altering the original array.
 *
 * @return array<array-key, mixed> The updated ( or copied and modified ) array.
 *
 * @throws InvalidArgumentException If `$key` is null and `$value` is not an array.
 *
 * @example
 * ```php
 * $data = [];
 * set( $data , 'user.name' , 'Alice' ) ;
 * // $data => ['user' => ['name' => 'Alice']]
 *
 * set( $data , null, ['id' => 1] ) ;
 * // $data => ['id' => 1]
 *
 * $data = ['user' => ['name' => 'Marc']];
 * $new = set($data, 'user.address.country', 'France', '.', true);
 * print_r( $new ); // ['user' => ['name' => 'Marc', 'address' => ['country' => 'France']]]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function set
(
      array &$array ,
    ?string $key    ,
      mixed $value  ,
     string $separator = '.' ,
       bool $copy = false
)
:array
{
    if ( $copy )
    {
        $copied = unserialize( serialize( $array ) ) ; // Deep copy
        $array  = is_array( $copied ) ? $copied : $array ; // round-tripping our own serialize() cannot fail
    }

    if ( is_null( $key ) )
    {
        if ( !is_array( $value ) )
        {
            throw new InvalidArgumentException( 'The $value argument must be an array when $key is null, the whole array being replaced.' ) ;
        }
        return $array = $value ;
    }

    $keys = explode( $separator , $key ) ;

    while ( count($keys) > 1 )
    {
        $key = array_shift($keys ) ;

        // If the key doesn't exist at this depth, we will just create an empty array
        // to hold the next value, allowing us to create the arrays to hold final
        // values at the correct depth. Then we'll keep digging into the array.
        if ( !isset( $array[ $key ] ) || !is_array( $array[ $key ] ) )
        {
            $array[ $key ] = [] ;
        }

        $array = &$array[ $key ] ;
    }

    $array[ array_shift($keys) ] = $value;

    return $array;
}