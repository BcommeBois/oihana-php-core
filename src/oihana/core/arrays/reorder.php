<?php

namespace oihana\core\arrays ;

/**
 * Reorders an associative array by placing specified keys first, optionally sorting the rest.
 *
 * This function is useful for JSON serialization where key order matters (e.g., @type, @context first).
 *
 * @param array    $array     The input associative array.
 * @param string[] $firstKeys Keys that should appear first, in the given order.
 * @param bool     $sort      Whether to sort remaining keys alphabetically (default: true).
 *
 * @return array The reordered array.
 *
 * @example Basic reordering
 * ```php
 * $data = ['name' => 'Alice', 'id' => 1, 'type' => 'User'];
 * $result = reorder($data, ['type', 'id']);
 * // ['type' => 'User', 'id' => 1, 'name' => 'Alice']
 * ```
 *
 * @example With alphabetical sorting
 * ```php
 * $data = ['z' => 1, 'a' => 2, 'type' => 3];
 * $result = reorder($data, ['type'], true);
 * // ['type' => 3, 'a' => 2, 'z' => 1]
 * ```
 *
 * @example Non-existent keys are ignored
 * ```php
 * $data = ['name' => 'Bob'];
 * $result = reorder($data, ['id', 'name']);
 * // ['name' => 'Bob'] (id doesn't exist, so it's skipped)
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz
 * @since   1.0.8
 */
function reorder
(
    array $array ,
    array $firstKeys = [] ,
    bool  $sort      = true
)
:array
{
    if ( empty( $firstKeys ) )
    {
        if ( $sort )
        {
            ksort($array , SORT_STRING ) ;
        }
        return $array ;
    }

    $ordered = [];

    // Extract priority keys in order
    foreach ( $firstKeys as $key )
    {
        if ( array_key_exists( $key , $array ) )
        {
            $ordered[ $key ] = $array[ $key ] ;
            unset( $array[ $key ] ) ;
        }
    }

    // Sort remaining keys if requested
    if ( $sort && !empty( $array ) )
    {
        ksort($array , SORT_STRING ) ;
    }

    return $ordered + $array ;
}