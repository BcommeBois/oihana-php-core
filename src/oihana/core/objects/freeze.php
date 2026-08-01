<?php

namespace oihana\core\objects ;

use InvalidArgumentException ;

use oihana\core\arrays\CleanFlag ;

use function oihana\core\arrays\clean ;

/**
 * Builds a plain associative array snapshot of the given properties of an object.
 *
 * Typical use case: freezing a reference to another document. A caller names a record,
 * the server re-reads it and copies **the properties it chooses** onto the current
 * document, so the snapshot survives later changes to the source.
 *
 * ### Property selection and renaming
 *
 * Each entry of `$fields` is the name of a source property. When the entry carries a
 * **string key**, that key becomes the name of the property in the snapshot — which lets
 * a `name` property land as `thingName` on the carrying document:
 * ```php
 * [ '_key' , 'url' , 'thingName' => 'name' ]
 * ```
 *
 * ### Reading
 *
 * Properties are read with `$object->{ $field } ?? null`, so magic `__get()` / `__isset()`
 * accessors are honoured — unlike {@see pick()}, which relies on `get_object_vars()`.
 * A property that is missing, uninitialized or inaccessible therefore reads as `null`,
 * and is dropped as long as `$flags` discards nulls (which the default does).
 *
 * ### Filtering
 *
 * The collected values are handed to {@see clean()} with `$flags`, so the whole
 * `CleanFlag` vocabulary applies. The default, `CleanFlag::NULLS`, only discards `null` —
 * `0`, `0.0`, `''`, `false` and `[]` are kept. Note that `CleanFlag::TRIM` is a modifier of
 * `CleanFlag::EMPTY` and does nothing on its own, and that `CleanFlag::FALSY` short-circuits
 * `NULLS` / `EMPTY` / `TRIM` and never applies to arrays. `CleanFlag::RETURN_NULL` is rejected:
 * this function always returns an array.
 *
 * ### Depth
 *
 * By default an object value is copied by handle, so the snapshot keeps sharing the
 * instance with the source. Pass `$deep = true` to convert every object or array value
 * into a plain associative array with {@see toAssociativeArray()}, which makes the
 * snapshot genuinely inert.
 *
 * The returned array follows the order of `$fields`, not the declaration order of the
 * object. The source object is never modified.
 *
 * @param object                 $object The source object.
 * @param array<int|string,string> $fields The properties to copy. An integer key means the
 *                                       source name is reused as-is ; a string key renames
 *                                       the property in the snapshot.
 * @param int                    $flags  A bitmask of {@see CleanFlag} values applied to the
 *                                       collected values. Defaults to `CleanFlag::NULLS`.
 * @param bool                   $deep   If true, object and array values are converted into
 *                                       plain associative arrays. Defaults to false.
 *
 * @return array<int|string,mixed> The frozen snapshot, in the order of `$fields`. Keys are the
 *                                 property names, except for the numeric ones that PHP casts to
 *                                 integers, as in any array.
 *
 * @throws InvalidArgumentException If `$flags` contains `CleanFlag::RETURN_NULL`, or is not a
 *                                  valid combination of `CleanFlag` constants.
 *
 * @example Basic snapshot
 * ```php
 * use function oihana\core\objects\freeze;
 *
 * $thing = (object) [ '_key' => 'aeb1' , 'id' => null , 'name' => 'Alice' , 'score' => 0 ] ;
 *
 * $frozen = freeze( $thing , [ 'name' , '_key' , 'id' , 'url' ] ) ;
 * // [ 'name' => 'Alice' , '_key' => 'aeb1' ]
 * // 'id' is null and 'url' does not exist : both are dropped.
 * ```
 *
 * @example Renaming properties
 * ```php
 * $frozen = freeze( $thing , [ '_key' , 'thingName' => 'name' ] ) ;
 * // [ '_key' => 'aeb1' , 'thingName' => 'Alice' ]
 * ```
 *
 * @example Discarding empty strings and empty arrays too
 * ```php
 * use oihana\core\arrays\CleanFlag;
 *
 * $thing = (object) [ 'name' => 'Alice' , 'label' => '   ' , 'tags' => [] , 'score' => 0 ] ;
 *
 * $frozen = freeze( $thing , [ 'name' , 'label' , 'tags' , 'score' ] , CleanFlag::MAIN ) ;
 * // [ 'name' => 'Alice' , 'score' => 0 ]
 * ```
 *
 * @example Inert snapshot of a nested object
 * ```php
 * $thing = (object) [ 'name' => 'Alice' , 'address' => (object) [ 'city' => 'Paris' ] ] ;
 *
 * $frozen = freeze( $thing , [ 'name' , 'address' ] , deep : true ) ;
 * // [ 'name' => 'Alice' , 'address' => [ 'city' => 'Paris' ] ]
 * // Mutating $thing->address afterwards no longer alters $frozen.
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function freeze( object $object , array $fields , int $flags = CleanFlag::NULLS , bool $deep = false ): array
{
    if ( ( $flags & CleanFlag::RETURN_NULL ) !== 0 )
    {
        throw new InvalidArgumentException
        (
            'freeze() cannot honour CleanFlag::RETURN_NULL : the function always returns an array. '
          . 'Apply the ?: operator on the result instead.'
        ) ;
    }

    $frozen = [] ;

    foreach ( $fields as $key => $field )
    {
        $value = $object->{ $field } ?? null ;

        if ( $deep && ( is_object( $value ) || is_array( $value ) ) )
        {
            $value = toAssociativeArray( $value , strict : true ) ;
        }

        $frozen[ is_string( $key ) ? $key : $field ] = $value ;
    }

    return clean( $frozen , $flags ) ?? [] ;
}
