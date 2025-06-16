<?php

namespace oihana\core\objects ;

use InvalidArgumentException;
use function oihana\core\helpers\conditions;
use function oihana\core\arrays\compress as compressArray ;

/**
 * Compress the passed in object and remove all the empty properties.
 * <p>Usage :</p>
 * ```
 * use function core\objects\compress ;
 *
 * $object = new stdClass();
 *
 * $object->id          = 1;
 * $object->created     = null;
 * $object->name        = "hello world";
 * $object->description = null;
 *
 * echo json_encode( Objects::compress( $object , ["description"] ) ) ;
 * ```
 * @param object $object The object to compress.
 * @param array|null $options Optional configuration:
 * - 'conditions' (callable|array<callable>) : One or more functions used to determine whether a value should be removed.
 * - 'excludes'   (array<string>) : List of property names to exclude from filtering.
 * - 'recursive'  (bool) : If true (default), recursively compress nested objects.
 * - 'throwable'  (bool) : If true (default), throws InvalidArgumentException for invalid callbacks.
 * @param int $currentDepth Used internally to track recursion depth.
 * @return object The compressed object, with properties removed according to the provided conditions.
 * @throws InvalidArgumentException If invalid condition callbacks are provided and 'throwable' is true.
 */
function compress( object $object , ?array $options = [] , int $currentDepth = 0 ): object
{
    $excludes   = $options[ 'excludes'   ] ?? null  ;
    $maxDepth   = $options[ 'depth'      ] ?? null  ;
    $recursive  = $options[ 'recursive'  ] ?? false ;
    $throwable  = $options[ 'throwable'  ] ?? true  ;

    $conditions = conditions( $options[ 'conditions' ] ?? null , $throwable ) ;

    $properties = get_object_vars( $object );
    foreach( $properties as $key => $value )
    {
        if( is_array( $excludes ) && in_array( $key , $excludes , true ) )
        {
            continue ;
        }

        if ( is_object( $value ) && $recursive && ( $maxDepth === null || $currentDepth < $maxDepth ) )
        {
            $object->{ $key } = compress( $value , $options , $currentDepth + 1 ) ;
            continue;
        }
        elseif ( is_array($value) && $recursive && ($maxDepth === null || $currentDepth < $maxDepth ) )
        {
            $object->{ $key } = compressArray( $value , $options , $currentDepth + 1 ) ;
            continue;
        }

        foreach ( $conditions as $condition )
        {
            if ( $condition( $value ) )
            {
                unset( $object->{ $key } ) ;
                break ;
            }
        }
    }
    return $object;
}