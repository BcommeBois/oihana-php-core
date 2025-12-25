<?php

namespace oihana\core\json ;

use JsonSerializable;

/**
 * Recursively serialize all JsonSerializable values within an array or object.
 *
 * This function traverses arrays and objects at any depth, and whenever it finds
 * a value that implements JsonSerializable, it calls jsonSerialize() on it.
 *
 * @param mixed $value The value to serialize.
 * @param int $currentDepth Internal recursion counter (default 0).
 *
 * @return mixed The value with all JsonSerializable elements serialized.
 *
 * @example
 * ```php
 * $data =
 * [
 *     'user' => new User(),       // implements JsonSerializable
 *     'tags' => ['a', 'b'],
 * ];
 *
 * $result = deepJsonSerialize($data);
 * // All JsonSerializable objects will be converted to arrays/values recursively
 * ```
 *
 * @package oihana\core\json
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.6
 */
function deepJsonSerialize( mixed $value , int $currentDepth = 0 ) :mixed
{
    if ( $value instanceof JsonSerializable )
    {
        $value = $value->jsonSerialize() ;
    }

    if ( is_array( $value ) )
    {
        foreach ( $value as $k => $v )
        {
            $value[ $k ] = deepJsonSerialize( $v , $currentDepth + 1 ) ;
        }
    }
    else if ( is_object( $value ) )
    {
        foreach ( get_object_vars( $value ) as $k => $v )
        {
            $value->{ $k } = deepJsonSerialize( $v , $currentDepth + 1 ) ;
        }
    }

    return $value ;
}