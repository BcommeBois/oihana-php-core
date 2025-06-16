<?php

namespace oihana\core\arrays ;

use function oihana\core\helpers\conditions;
use function oihana\core\objects\compress as compressObject ;

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
 * @param array|null $options Optional configuration:
 * - 'clone' (bool>) : Indicates if the array is cloned or not.
 * - 'conditions' (callable|array<callable>) : One or more functions used to determine whether a value should be removed.
 * - 'excludes'   (array<string>) : List of property names to exclude from filtering.
 * - 'recursive'  (bool) : If true (default), recursively compress nested objects.
 * - 'throwable'  (bool) : If true (default), throws InvalidArgumentException for invalid callbacks.
 * @return array The passed-in array or a clone reference compressed.
 */
function compress( array $array ,  ?array $options = [], int $currentDepth = 0 ): array
{
    $clone      = $options[ 'clone'     ] ?? false ;
    $excludes   = $options[ 'excludes'  ] ?? null ;
    $maxDepth   = $options[ 'depth'     ] ?? null ;
    $recursive  = $options[ 'recursive' ] ?? false ;
    $throwable  = $options[ 'throwable' ] ?? true ;

    $array = $clone ? [ ...$array ] : $array ;

    $conditions = conditions( $options[ 'conditions' ] ?? null , $throwable ) ;

    foreach ( $array as $key => $value )
    {
        if ( is_array( $excludes ) && in_array( $key , $excludes , true ) )
        {
            continue;
        }

        if ( is_object($value) && $recursive && ( $maxDepth === null || $currentDepth < $maxDepth ) )
        {
            $array[ $key ] = compressObject( $value , $options , $currentDepth + 1) ;
            continue;
        }

        if( is_array( $value ) && $recursive && ($maxDepth === null || $currentDepth < $maxDepth))
        {
            $array[ $key ] = compress( $value , $options , $currentDepth + 1 ) ;
            continue;
        }

        foreach ( $conditions as $condition )
        {
            if ( $condition( $value ) )
            {
                unset( $array[ $key ] ) ;
                break ;
            }
        }
    }

    if ( hasIntKeys( $array ) )
    {
        $array = array_values( $array ) ;
    }

    return $array;
}