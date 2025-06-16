<?php

namespace oihana\core\arrays ;

/**
 * Compress the passed in indexed object and remove all the empty properties.
 * <p>Usage :</p>
 * <pre>
 * use function core\arrays\compress ;
 * $array =
 * [
 *   'id'          => 1 ,
 *   'created'     => null ,
 *   'name'        => 'hello world' ,
 *   'description' => null
 * ];
 * echo json_encode( compress( $array , [ 'description' ] ) ) ;
 * @param array $array The array to compress
 * @param ?array $excludes The list of properties to exclude.
 * @param bool $clone Indicates if the array is cloned or not.
 * @return array The passed-in array or a clone reference compressed.
 */
function compress( array $array , ?array $excludes = null , bool $clone = false ): array
{
    $ar = $clone ? [ ...$array ] : $array ;
    foreach( $ar as $key => $value )
    {
        if( is_array( $excludes ) && in_array( $key , $excludes ) )
        {
            continue ;
        }

        if( !isset( $value ) )
        {
            unset( $ar[$key] );
        }
    }
    return $ar ;
}