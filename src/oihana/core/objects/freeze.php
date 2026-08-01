<?php

namespace oihana\core\objects ;

/**
 * Builds a plain associative array snapshot of the given properties of an object,
 * keeping only the non-null ones.
 *
 * Typical use case: freezing a reference to another document. A caller names a record,
 * the server re-reads it and copies **the properties it chooses** onto the current
 * document, so the snapshot survives later changes to the source.
 *
 * Behaviour:
 * - The returned array follows the order of `$fields`, not the declaration order of the object.
 * - Properties are read with `$object->{ $field } ?? null`, so magic `__get()` / `__isset()`
 *   accessors are honoured — unlike {@see pick()}, which relies on `get_object_vars()`.
 * - Missing, uninitialized and inaccessible properties are silently skipped, as are the
 *   properties whose value is `null`.
 * - Only `null` is discarded: `0`, `0.0`, `''`, `false` and `[]` are kept.
 * - The source object is never modified.
 *
 * @param object            $object The source object.
 * @param array<int,string> $fields The list of property names to copy.
 *
 * @return array<string,mixed> The frozen snapshot, in the order of `$fields`.
 *
 * @example
 * ```php
 * use function oihana\core\objects\freeze;
 *
 * $thing = (object)
 * [
 *     '_key'  => 'aeb1' ,
 *     'id'    => null ,
 *     'name'  => 'Alice' ,
 *     'score' => 0 ,
 * ] ;
 *
 * $frozen = freeze( $thing , [ 'name' , '_key' , 'id' , 'url' ] ) ;
 * // [ 'name' => 'Alice' , '_key' => 'aeb1' ]
 * // 'id' is null and 'url' does not exist : both are dropped.
 * ```
 *
 * @example Falsy values are preserved
 * ```php
 * $frozen = freeze( (object) [ 'score' => 0 , 'label' => '' , 'active' => false ] , [ 'score' , 'label' , 'active' ] ) ;
 * // [ 'score' => 0 , 'label' => '' , 'active' => false ]
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function freeze( object $object , array $fields ): array
{
    $frozen = [] ;

    foreach ( $fields as $field )
    {
        $value = $object->{ $field } ?? null ;

        if ( $value !== null )
        {
            $frozen[ $field ] = $value ;
        }
    }

    return $frozen ;
}
