<?php

namespace oihana\core\arrays ;

/**
 * Groups the items of an array into buckets keyed by a computed value.
 *
 * The `$keyer` callback is invoked as `fn( $value , $key )` for every entry and
 * must return the group key. Non `int|string` keys are cast to `string`.
 * Original keys are preserved inside each bucket.
 *
 * @param array<int|string, mixed> $items The array to group.
 * @param callable $keyer The grouping callback: `fn( $value , $key ): int|string`.
 *
 * @return array<int|string, array<int|string, mixed>> An associative array of `groupKey => [ originalKey => value, … ]` buckets.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\groupBy;
 *
 * $people =
 * [
 *     [ 'name' => 'Alice' , 'city' => 'Paris'  ] ,
 *     [ 'name' => 'Bob'   , 'city' => 'Lyon'   ] ,
 *     [ 'name' => 'Carol' , 'city' => 'Paris'  ] ,
 * ];
 *
 * $byCity = groupBy( $people , fn( $p ) => $p[ 'city' ] ) ;
 * // [ 'Paris' => [ 0 => Alice, 2 => Carol ] , 'Lyon' => [ 1 => Bob ] ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function groupBy( array $items , callable $keyer ) :array
{
    $result = [] ;
    foreach ( $items as $key => $value )
    {
        $groupKey = $keyer( $value , $key ) ;
        if ( !is_int( $groupKey ) && !is_string( $groupKey ) )
        {
            $groupKey = (string) $groupKey ;
        }
        $result[ $groupKey ][ $key ] = $value ;
    }
    return $result ;
}
