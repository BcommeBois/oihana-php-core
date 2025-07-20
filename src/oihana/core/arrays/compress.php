<?php

namespace oihana\core\arrays ;

use function oihana\core\helpers\conditions;
use function oihana\core\objects\compress as compressObject ;

/**
 * Compresses the given array by removing values that match one or more conditions.
 *
 * Useful for cleaning up associative arrays (e.g., from form submissions or object exports)
 * by removing nulls, empty strings, or other unwanted values. Supports recursion into nested arrays or objects.
 *
 * @param array $array        The input array to compress.
 * @param array|null $options Optional configuration:
 * - **clone** (bool)         If `true`, works on a cloned copy of the array. Original remains unchanged. *(default: false)*
 * - **conditions** (callable|array<callable>)
 *                            One or more callbacks: `(mixed $value): bool`.
 *                            If any condition returns `true`, the value is removed.
 *                            *( default: `fn($v) => is_null($v)` )*
 * - **excludes** (string[])  List of keys to exclude from filtering, even if matched by a condition.
 * - **recursive** (bool)     Whether to recursively compress nested arrays or objects. *(default: true)*
 * - **depth** (int|null)     Maximum depth for recursion. `null` means no limit.
 * - **throwable** (bool)     If `true`, throws `InvalidArgumentException` for invalid conditions. *(default: true)*
 * @param int $currentDepth (Internal) Tracks current recursion depth. You usually don’t need to set this.
 *
 * @return array The compressed array (or its clone if `clone=true`).
 *
 * @example
 * Basic cleanup of null values
 * ```php
 * use function oihana\core\arrays\compress;
 * $data = ['id' => 1, 'name' => 'hello', 'description' => null];
 * $clean = compress($data);
 * // Result: ['id' => 1, 'name' => 'hello']
 * ```
 *
 * Exclude a specific key from filtering
 * ```php
 * $data = ['id' => 1, 'name' => null];
 * $clean = compress($data, ['excludes' => ['name']]);
 * // Result: ['id' => 1, 'name' => null]  // "name" is preserved
 * ```
 *
 * Remove null or empty strings
 * ```php
 * $conditions =
 * [
 *     fn($v) => is_null($v),
 *     fn($v) => is_string($v) && $v === ''
 * ];
 * $data  = ['a' => '', 'b' => 0, 'c' => null];
 * $clean = compress($data, ['conditions' => $conditions]);
 * // Result: ['b' => 0]
 * ```
 *
 * Recursive compression with depth limit
 * ```php
 * $nested =
 * [
 *     'id'   => 2,
 *     'meta' => ['created' => null, 'tags' => []],
 * ];
 *
 * $clean = compress($nested,
 * [
 *     'conditions' => fn($v) => $v === [] || $v === null,
 *     'depth'      => 1
 * ]);
 * // Result: ['id' => 2]
 * ```
 *
 * Clone input before compressing
 * ```php
 * $original = ['x' => null, 'y' => 5];
 * $copy     = compress($original, ['clone' => true]);
 * // $original remains unchanged; $copy == ['y' => 5]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
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