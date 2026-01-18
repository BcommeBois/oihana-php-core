<?php

namespace oihana\core\arrays ;

/**
 * Omits a set of keys from an array.
 *
 * This function is a semantic alias of {@see removeKeys()}.
 * It returns a new array without the specified keys.
 *
 * The naming is intentionally security- and data-filtering-oriented,
 * making it well suited for excluding sensitive or unwanted fields
 * (e.g. passwords, tokens, internal metadata).
 *
 * @param array $array The input array to filter.
 * @param array $keys  The list of keys to omit from the array.
 *
 * @return array The filtered array without the specified keys.
 *
 * @example
 * ```php
 * $data =
 * [
 *     'id'       => 42,
 *     'name'     => 'Alice',
 *     'email'    => 'alice@example.com',
 *     'password' => 'secret',
 * ];
 *
 * $result = omit( $data , [ 'password' , 'email' ] );
 *
 * // [
 * //     'id'   => 42,
 * //     'name' => 'Alice',
 * // ]
 * ```
 *
 * @see  removeKeys()
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function omit( array $array , array $keys ) :array
{
    return removeKeys( $array , $keys ) ;
}