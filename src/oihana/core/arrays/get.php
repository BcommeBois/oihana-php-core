<?php

namespace oihana\core\arrays ;

/**
 * Retrieves a value from an associative array using a key path.
 *
 * This function allows navigation through a nested array using a key
 * composed of segments separated by a specified separator (default is a dot).
 * If the key is not found, the function returns a default value.
 *
 * @param array $array The associative array to search within.
 * @param string|null $key The key path as a string.
 *                         Can be null, in which case the function returns the entire array.
 * @param mixed $default The default value to return if the key is not found.
 *                        Defaults to null.
 * @param string $separator The separator used to split the key into segments.
 *                          Defaults to a dot ('.').
 *
 * @return mixed The value found in the array or the default value if the key does not exist.
 *
 * @example
 * ```php
 * $data =
 * [
 *     'user' =>
 *     [
 *         'name'    => 'Alice',
 *         'address' =>
 *         [
 *             'city' => 'Paris',
 *             'geo' => [ 'lat' => 48.8566, 'lng' => 2.3522 ],
 *         ],
 *     ],
 * ];
 *
 * echo get($data, 'user.name');             // Alice
 * echo get($data, 'user.address.city');     // Paris
 * echo get($data, 'user.address.geo.lat');  // 48.8566
 * echo get($data, 'user.phone', 'unknown'); // unknown
 * echo get($data, null);                    // returns entire $data array
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function get( array $array , ?string $key , mixed $default = null , string $separator = '.' ) :mixed
{
    if ( is_null( $key ) )
    {
        return $array;
    }

    if ( array_key_exists( $key, $array ) )
    {
        return $array[ $key ] ;
    }

    $segments = explode( $separator , $key ) ;

    foreach ( $segments as $segment )
    {
        if ( is_array( $array ) && array_key_exists( $segment , $array ) )
        {
            $array = $array[ $segment ] ;
        }
        else
        {
            return is_callable( $default ) ? $default() : $default ;
        }
    }

    return $array ;
}