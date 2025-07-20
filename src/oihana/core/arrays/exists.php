<?php

namespace oihana\core\arrays ;

use ArrayAccess ;

/**
 * Checks if the given key exists in the provided array.
 * @param array|ArrayAccess $array
 * @param string|int|null $key
 * @return bool
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function exists( array|ArrayAccess $array , null|string|int $key ) :bool
{
    if( !isset( $key ) || $key === '' )
    {
        return false ;
    }

    if ($array instanceof ArrayAccess )
    {
        return $array->offsetExists( $key ) ;
    }

    return array_key_exists( $key , $array );
}