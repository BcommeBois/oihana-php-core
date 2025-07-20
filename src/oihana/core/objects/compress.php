<?php

namespace oihana\core\objects ;

use InvalidArgumentException;
use function oihana\core\helpers\conditions;
use function oihana\core\arrays\compress as compressArray ;

/**
 * Compress the passed in object by removing all properties that match given conditions.
 *
 * @param object $object The object to compress.
 * @param array{ conditions:callable|callable[] , depth:null|int , excludes:string[] , recursive:bool , throwable:bool } $options Optional configuration:
 * - 'conditions' (callable|array<callable>) : One or more functions used to determine whether a value should be removed.
 * - 'depth'   (array<string>) : List of property names to exclude from filtering.
 * - 'excludes'   (array<string>) : List of property names to exclude from filtering.
 * - 'recursive'  (bool) : If true (default), recursively compress nested objects.
 * - 'throwable'  (bool) : If true (default), throws InvalidArgumentException for invalid callbacks.
 * @param int $currentDepth Used internally to track recursion depth.
 *
 * @return object The compressed object, with properties removed according to the provided conditions.
 *
 * @throws InvalidArgumentException If invalid condition callbacks are provided and 'throwable' is true.
 *
 * @example
 * ```php
 * use function oihana\core\objects\compress;
 *
 * $obj = new stdClass();
 * $obj->id = 1;
 * $obj->created = null;
 * $obj->name = "hello world";
 * $obj->description = null;
 *
 * $compressed = compress($obj, [
 *     'conditions' => [fn($v) => $v === null],
 *     'excludes' => ['id'],
 *     'recursive' => true,
 * ]);
 * echo json_encode($compressed);
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function compress( object $object , array $options = [] , int $currentDepth = 0 ): object
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