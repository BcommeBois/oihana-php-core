<?php

namespace oihana\core\arrays ;

/**
 * Indicates if all the keys in an array are integers.
 * @param array $array
 * @return bool
 */
function hasIntKeys( array $array ) :bool
{
    return array_all( array_keys( $array ) , fn( $key ) => is_int( $key ) ) ;
}