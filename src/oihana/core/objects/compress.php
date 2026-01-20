<?php

namespace oihana\core\objects ;

use InvalidArgumentException;

use oihana\core\options\CompressOption;

use ReflectionException;
use function oihana\core\arrays\compress as compressArray ;
use function oihana\core\callables\countCallableParam;
use function oihana\core\callables\isCallable;
use function oihana\core\callables\resolveCallable;

/**
 * Compress the given object by removing properties that match certain conditions.
 *
 * This function traverses the object and removes properties according to the provided
 * options. It can operate recursively on nested objects and arrays.
 *
 * @param object $object The object to compress.
 * @param array{
 *     conditions?: callable|callable[] ,   // One or more callbacks to decide if a value should be removed.
 *     depth?: null|int ,                   // Maximum recursion depth (null = unlimited).
 *     excludes?: string[] ,                // Property names to exclude from filtering.
 *     recursive?: bool ,                   // Whether to recursively compress nested objects/arrays.
 *     removeKeys?: string[] ,              // List of keys/properties to always remove.
 *     throwable?: bool                     // If true, throws InvalidArgumentException on invalid callbacks.
 * } $options Optional configuration.
 * @param int $currentDepth Internal counter used to track recursion depth.
 *
 * @return object The compressed object, with properties removed according to the rules.
 *
 * @throws InvalidArgumentException If invalid callbacks are provided and 'throwable' is true.
 *
 * @example Basic removal of null values
 * ```php
 * use function oihana\core\objects\compress;
 *
 * $obj = (object)[
 *     'id'          => 1,
 *     'name'        => 'hello',
 *     'description' => null,
 * ];
 *
 * $result = compress($obj, [
 *     'conditions' => fn($v) => $v === null,
 * ]);
 *
 * // Result: { "id":1, "name":"hello" }
 * echo json_encode($result);
 * ```
 *
 * @example Excluding certain properties
 * ```php
 * $obj = (object)[
 *     'id'    => 1,
 *     'debug' => 'keep me',
 *     'temp'  => null,
 * ];
 *
 * $result = compress($obj, [
 *     'conditions' => fn($v) => $v === null,
 *     'excludes'   => ['debug'],
 * ]);
 *
 * // Result: { "id":1, "debug":"keep me" }
 * echo json_encode($result);
 * ```
 *
 * @example Removing properties by name
 * ```php
 * $obj = (object)[
 *     'id'    => 1,
 *     'token' => 'secret',
 *     'name'  => 'test',
 * ];
 *
 * $result = compress($obj, [
 *     'removeKeys' => ['token'],
 * ]);
 *
 * // Result: { "id":1, "name":"test" }
 * echo json_encode($result);
 * ```
 *
 * @example Recursive compression
 * ```php
 * $obj = (object)[
 *     'id'    => 1,
 *     'child' => (object)[
 *         'value' => null,
 *         'label' => 'ok',
 *     ],
 * ];
 *
 * $result = compress($obj, [
 *     'conditions' => fn($v) => $v === null,
 *     'recursive'  => true,
 * ]);
 *
 * // Result: { "id":1, "child":{ "label":"ok" } }
 * echo json_encode($result);
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function compress( object $object , array $options = [] , int $currentDepth = 0 ): object
{
    $options = CompressOption::normalize($options);

    $conditions = $options[ CompressOption::CONDITIONS  ] ;
    $excludes   = $options[ CompressOption::EXCLUDES    ] ;
    $maxDepth   = $options[ CompressOption::DEPTH       ] ;
    $recursive  = $options[ CompressOption::RECURSIVE   ] ;
    $removeKeys = $options[ CompressOption::REMOVE_KEYS ] ;

    $properties = get_object_vars( $object ) ;

    foreach( $properties as $key => $value )
    {
        if (is_array( $removeKeys ) && in_array( $key , $removeKeys , true ) )
        {
            unset($object->{$key});
            continue;
        }

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

        if ( !empty( $conditions ) && is_iterable( $conditions ) )
        {
            foreach ( $conditions as $condition )
            {
                try
                {
                    $paramCount = countCallableParam( $condition ) ;
                    $valid      = $paramCount === 2 ? $condition( $value , $key ) : $condition( $value ) ;
                    if ( $valid )
                    {
                        unset( $object->{ $key } );
                        break;
                    }
                }
                catch ( InvalidArgumentException | ReflectionException )
                {
                    continue ;
                }
            }
        }
    }
    return $object ;
}