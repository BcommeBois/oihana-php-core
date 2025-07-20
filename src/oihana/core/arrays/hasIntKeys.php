<?php

namespace oihana\core\arrays ;

/**
 * Indicates if all the keys in an array are integers.
 * @param array $array
 * @return bool
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function hasIntKeys( array $array ) :bool
{
    return array_all( array_keys( $array ) , fn( $key ) => is_int( $key ) ) ;
}