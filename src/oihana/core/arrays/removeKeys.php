<?php

namespace oihana\core\arrays ;

/**
 * Removes in an array a set of keys.
 * @param array $array The array to evaluates
 * @param array $keys The list of all keys to remove
 * @param bool $clone Indicates if the array is cloned or not.
 * @return array The passed-in array or a clone reference
 */
function removeKeys( array $array , array $keys = [] , bool $clone = false ): array
{
    $ar = $clone ? [ ...$array ] : $array ;
    if( !empty( $ar ) )
    {
        foreach( $keys as $key )
        {
            if( array_key_exists( $key , $ar ) )
            {
                unset( $ar[ $key ] );
            }
        }
    }
    return $ar  ;
}