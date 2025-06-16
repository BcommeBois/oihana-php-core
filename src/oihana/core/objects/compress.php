<?php

namespace oihana\core\objects ;

use InvalidArgumentException;

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
    $conditions = $options[ 'conditions' ] ?? null  ;
    $excludes   = $options[ 'excludes'   ] ?? null  ;
    $maxDepth   = $options[ 'depth'      ] ?? null  ;
    $recursive  = $options[ 'recursive'  ] ?? false ;
    $throwable  = $options[ 'throwable'  ] ?? true  ;

    if ( $conditions === null )
    {
        $conditions = [ fn( $value ) => is_null( $value ) ] ;
    }
    elseif ( is_callable( $conditions ) )
    {
        $conditions = [ $conditions ] ;
    }
    elseif ( is_array( $conditions ) )
    {
        $conditions = array_filter( $conditions , function ( $condition ) use ( $throwable )
        {
            if ( !is_callable( $condition ) )
            {
                if ( $throwable )
                {
                    throw new InvalidArgumentException("All conditions must be callable.");
                }
                return false ;
            }
            return true ;
        });
    }
    else
    {
        if ( $throwable )
        {
            throw new InvalidArgumentException("Le paramètre conditions doit être callable, tableau ou null");
        }
        $conditions = [];
    }

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
            $object->{ $key } = array_map( fn( $item ) => is_object( $item )? compress( $item , $options , $currentDepth + 1 ) : $item , $value ) ;
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