<?php

namespace oihana\core\arrays ;

/**
 * Keeps only the specified keys from an array (inverse of removeKeys).
 *
 * This function returns a new array containing only the entries
 * whose keys are present in the given keys list.
 *
 * @param array $array The target array to filter.
 * @param array $keys  The list of keys to keep.
 *
 * @return array The filtered array containing only the specified keys.
 *
 * @example
 * ```php
 * $data = [
 *     'id'    => 42,
 *     'name'  => 'Alice',
 *     'email' => 'alice@example.com',
 *     'role'  => 'admin',
 * ];
 *
 * $result = pick( $data , [ 'id' , 'name' ] );
 *
 * // [
 * //     'id'   => 42,
 * //     'name' => 'Alice',
 * // ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function pick( array $array , array $keys ): array
{
    return array_intersect_key( $array , array_flip( $keys ) ) ;
}