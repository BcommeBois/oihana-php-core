<?php

namespace oihana\core\arrays ;

/**
 * Indicates if all the keys in an array are strings.
 * @param array $array
 * @return bool
 */
function hasStringKeys( array $array ) :bool
{
    return array_all( array_keys( $array ) , fn( $key ) => is_string( $key ) ) ;
}