<?php

namespace oihana\core\arrays ;

/**
 * Unset an item on an array or object using dot notation.
 * @param mixed $target The associative array to search within.
 * @param string|array $key The key path as a string or array. If key == '*' all the fields a removed.
 * @return mixed The object.
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function delete( mixed $target , string|array $key , string $separator = '.' ) :mixed
{
    if ( !is_array( $target ) )
    {
        return $target ;
    }

    $segments = is_array( $key ) ? $key : explode($separator , $key ) ;
    $segment  = array_shift($segments );

    if( $segment == '*' ) // ALL
    {
        $target = [] ;
    }
    elseif( $segments )
    {
        if ( array_key_exists( $segment , $target ) )
        {
            delete( $target[ $segment ] , $segments ) ;
        }
    }
    elseif( array_key_exists( $segment , $target ) )
    {
        unset( $target[ $segment ] ) ;
    }

    return $target;
}