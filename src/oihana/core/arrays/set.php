<?php

namespace oihana\core\arrays ;

/**
 * Sets a value In an associative array using a key path.
 * If no key is given to the method, the entire array will be replaced.
 * @param array $array The associative array to search within.
 * @param string|null $key The key path as a string. Can be null, in which case the function returns the entire array.
 * @param mixed $value The value to apply.
 * @param string $separator The separator used to split the key into segments. Defaults to a dot ('.').
 * @return mixed The value found in the array or the default value if the key does not exist.
 */
function set( array &$array , ?string $key , mixed $value , string $separator = '.' ) :mixed
{
    if ( is_null( $key ) )
    {
        return $array = $value ;
    }

    $keys = explode( $separator , $key ) ;

    while ( count($keys) > 1 )
    {
        $key = array_shift($keys ) ;

        // If the key doesn't exist at this depth, we will just create an empty array
        // to hold the next value, allowing us to create the arrays to hold final
        // values at the correct depth. Then we'll keep digging into the array.
        if ( !isset( $array[$key] ) || !is_array( $array[$key] ) )
        {
            $array[ $key ] = [] ;
        }

        $array = &$array[ $key ] ;
    }

    $array[ array_shift($keys) ] = $value;

    return $array;
}