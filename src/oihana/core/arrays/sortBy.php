<?php

namespace oihana\core\arrays ;

/**
 * Returns a copy of an array sorted by a computed value.
 *
 * The `$selector` callback is invoked once per entry as `fn( $value , $key )` and
 * its result is used as the sort weight (compared with the spaceship operator
 * `<=>`). The sort is stable (PHP ≥ 8.0): entries with equal weights keep their
 * original relative order. Original keys are preserved.
 *
 * @param array<int|string, mixed> $items    The array to sort.
 * @param callable $selector The weight callback: `fn( $value , $key ): mixed`.
 * @param bool     $desc     Whether to sort in descending order. Default `false`.
 *
 * @return array<int|string, mixed> A new array sorted by the computed weights, keys preserved.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\sortBy;
 *
 * $people =
 * [
 *     [ 'name' => 'Alice' , 'age' => 30 ] ,
 *     [ 'name' => 'Bob'   , 'age' => 25 ] ,
 *     [ 'name' => 'Carol' , 'age' => 35 ] ,
 * ];
 *
 * $byAge = sortBy( $people , fn( $p ) => $p[ 'age' ] ) ;
 * // Bob (25) , Alice (30) , Carol (35)
 *
 * $byAgeDesc = sortBy( $people , fn( $p ) => $p[ 'age' ] , true ) ;
 * // Carol (35) , Alice (30) , Bob (25)
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function sortBy( array $items , callable $selector , bool $desc = false ) :array
{
    $weights = [] ;
    foreach ( $items as $key => $value )
    {
        $weights[ $key ] = $selector( $value , $key ) ;
    }
    uksort( $items , function( $a , $b ) use ( $weights , $desc )
    {
        $order = $weights[ $a ] <=> $weights[ $b ] ;
        return $desc ? -$order : $order ;
    } ) ;
    return $items ;
}
