<?php

namespace oihana\core\arrays ;

/**
 * Indexes the items of an array by a computed key.
 *
 * The `$keyer` callback is invoked as `fn( $value , $key )` for every entry and
 * must return the new key. Non `int|string` keys are cast to `string`. When two
 * items resolve to the same key, the last one wins.
 *
 * @param array<int|string, mixed> $items The array to index.
 * @param callable $keyer The indexing callback: `fn( $value , $key ): int|string`.
 *
 * @return array<int|string, mixed> An associative array of `computedKey => value`.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\keyBy;
 *
 * $users =
 * [
 *     [ 'id' => 10 , 'name' => 'Alice' ] ,
 *     [ 'id' => 20 , 'name' => 'Bob'   ] ,
 * ];
 *
 * $byId = keyBy( $users , fn( $u ) => $u[ 'id' ] ) ;
 * // [ 10 => [ 'id' => 10, 'name' => 'Alice' ] , 20 => [ 'id' => 20, 'name' => 'Bob' ] ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function keyBy( array $items , callable $keyer ) :array
{
    $result = [] ;
    foreach ( $items as $key => $value )
    {
        $newKey = $keyer( $value , $key ) ;
        if ( !is_int( $newKey ) && !is_string( $newKey ) )
        {
            $newKey = (string) $newKey ;
        }
        $result[ $newKey ] = $value ;
    }
    return $result ;
}
