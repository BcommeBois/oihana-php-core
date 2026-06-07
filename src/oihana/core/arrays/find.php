<?php

namespace oihana\core\arrays ;

/**
 * Returns the first element of an array that satisfies a predicate.
 *
 * The `$predicate` callback is invoked as `fn( $value , $key )` for every entry,
 * in iteration order, until it returns a truthy value. If no element matches, the
 * `$default` value is returned.
 *
 * @param array    $items     The array to search.
 * @param callable $predicate The test callback: `fn( $value , $key ): bool`.
 * @param mixed    $default   The value returned when no element matches. Default `null`.
 *
 * @return mixed The first matching value, or `$default`.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\find;
 *
 * $first = find( [ 1 , 3 , 4 , 7 ] , fn( $n ) => $n % 2 === 0 ) ;
 * // 4
 *
 * $none = find( [ 1 , 3 ] , fn( $n ) => $n > 10 , -1 ) ;
 * // -1
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function find( array $items , callable $predicate , mixed $default = null ) :mixed
{
    foreach ( $items as $key => $value )
    {
        if ( $predicate( $value , $key ) )
        {
            return $value ;
        }
    }
    return $default ;
}
