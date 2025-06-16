<?php

namespace oihana\core\arrays ;

use ArrayAccess ;

/**
 * Checks if the given key exists in the provided array.
 * @param array|ArrayAccess $array
 * @param string|int $key
 * @return bool
 */
function exists( array|ArrayAccess $array , string|int $key ) :bool
{
    if ($array instanceof ArrayAccess)
    {
        return $array->offsetExists( $key ) ;
    }

    return array_key_exists( $key , $array );
}