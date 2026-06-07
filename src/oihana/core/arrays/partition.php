<?php

namespace oihana\core\arrays ;

/**
 * Splits an array into two groups according to a predicate.
 *
 * The `$predicate` callback is invoked as `fn( $value , $key )` for every entry.
 * Entries for which it returns a truthy value go into the first group, the others
 * into the second. Original keys are preserved in both groups.
 *
 * @param array    $items     The array to split.
 * @param callable $predicate The test callback: `fn( $value , $key ): bool`.
 *
 * @return array A pair `[ 0 => $passed , 1 => $failed ]`.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\partition;
 *
 * [ $even , $odd ] = partition( [ 1 , 2 , 3 , 4 ] , fn( $n ) => $n % 2 === 0 ) ;
 * // $even === [ 1 => 2 , 3 => 4 ]
 * // $odd  === [ 0 => 1 , 2 => 3 ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function partition( array $items , callable $predicate ) :array
{
    $passed = [] ;
    $failed = [] ;
    foreach ( $items as $key => $value )
    {
        if ( $predicate( $value , $key ) )
        {
            $passed[ $key ] = $value ;
        }
        else
        {
            $failed[ $key ] = $value ;
        }
    }
    return [ $passed , $failed ] ;
}
