<?php

namespace oihana\core\arrays ;

use ArrayAccess ;
use oihana\enums\Char;

/**
 * Checks if the given key exists in the provided array.
 * @param array|ArrayAccess $array
 * @param string|int|null $key
 * @return bool
 */
function exists( array|ArrayAccess $array , null|string|int $key ) :bool
{
    if( is_null( $key ) || $key == Char::EMPTY )
    {
        return false ;
    }

    if ($array instanceof ArrayAccess )
    {
        return $array->offsetExists( $key ) ;
    }
    return array_key_exists( $key , $array );
}