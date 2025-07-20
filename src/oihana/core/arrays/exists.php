<?php

namespace oihana\core\arrays ;

use ArrayAccess ;

// composer test tests/oihana/core/arrays/ExistsTest.php

/**
 * Checks whether a key exists in an array or an object implementing ArrayAccess.
 *
 * - Returns `false` if the key is `null` or an empty string.
 * - Supports both native arrays and `ArrayAccess` objects.
 *
 * @param array|ArrayAccess $array     The array or object to inspect.
 * @param string|int|null   $key       The key to check for existence.
 * @param string            $separator The separator used in the key path. Default is '.'.
 *
 * @return bool True if the key exists, false otherwise.
 *
 * @example
 * ```php
 * use oihana\core\arrays\exists;
 *
 * // Flat array
 * $data = ['name' => 'Alice'];
 * var_dump(exists($data, 'name')); // true
 * var_dump(exists($data, 'age'));  // false
 *
 * // Nested array
 * $data = ['user' => ['address' => ['country' => 'France']]];
 * var_dump(exists($data, 'user.address.country')); // true
 * var_dump(exists($data, 'user.address.city'));    // false
 *
 * // Mixed with numeric keys
 * $data = ['items' => [0 => ['id' => 1], 1 => ['id' => 2]]];
 * var_dump(exists($data, 'items.1.id')); // true
 *
 * // With null or empty key
 * var_dump(exists($data, null)); // false
 * var_dump(exists($data, ''));   // false
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function exists( array|ArrayAccess $array , null|string|int $key , string $separator = '.' ) :bool
{
    if( !isset( $key ) || $key === '' )
    {
        return false ;
    }

    if ( is_int($key) )
    {
        return $array instanceof ArrayAccess ? $array->offsetExists( $key ) : array_key_exists( $key , $array ) ;
    }

    $keys    = explode( $separator , $key ) ;
    $current = $array;

    foreach ( $keys as $segment )
    {
        if ( is_array( $current ) )
        {
            if ( !array_key_exists( $segment , $current ) )
            {
                return false;
            }
            $current = $current[$segment] ;
        }
        else if ( $current instanceof ArrayAccess )
        {
            if ( !$current->offsetExists( $segment ) )
            {
                return false;
            }
            $current = $current[ $segment ] ;
        }
        else
        {
            return false;
        }
    }

    return true;
}