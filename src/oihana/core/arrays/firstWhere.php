<?php

namespace oihana\core\arrays ;

/**
 * Returns the first element of an array that satisfies a predicate.
 *
 * This is a readability alias of {@see find()} ; both names are kept for convenience.
 *
 * @param array<int|string, mixed> $items     The array to search.
 * @param callable $predicate The test callback: `fn( $value , $key ): bool`.
 * @param mixed    $default   The value returned when no element matches. Default `null`.
 *
 * @return mixed The first matching value, or `$default`.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\firstWhere;
 *
 * $users =
 * [
 *     [ 'name' => 'Alice' , 'active' => false ] ,
 *     [ 'name' => 'Bob'   , 'active' => true  ] ,
 * ];
 *
 * $active = firstWhere( $users , fn( $u ) => $u[ 'active' ] ) ;
 * // [ 'name' => 'Bob' , 'active' => true ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function firstWhere( array $items , callable $predicate , mixed $default = null ) :mixed
{
    return find( $items , $predicate , $default ) ;
}
