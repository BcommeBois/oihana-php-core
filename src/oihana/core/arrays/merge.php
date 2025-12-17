<?php

namespace oihana\core\arrays ;

use oihana\core\options\MergeOption;
use oihana\core\options\NullsOption;

/**
 * Merges two arrays using MergeOption normalized settings.
 *
 * This function provides a flexible way to merge arrays, supporting:
 * - Deep recursive merging of nested arrays.
 * - Indexed sub-array storage for multiple values.
 * - Unique value enforcement in lists.
 * - Configurable handling of null values ('skip', 'keep', 'overwrite').
 * - Optional cleaning of the result using CleanFlag constants.
 *
 * @param array $original The original array to merge into.
 * @param array $external The array whose values will be merged into the original.
 * @param array $options Optional MergeOption array to control merge behavior.
 *
 * @return array The merged array, optionally cleaned according to CleanFlag.
 *
 * @example
 * ```php
 * use oihana\core\arrays\merge;
 * use oihana\core\options\MergeOption;
 * use oihana\core\arrays\CleanFlag;
 *
 * $a =
 * [
 *    'users' =>
 *     [
 *         ['name' => 'Alice'],
 *         ['name' => 'Bob']
 *     ],
 *     'count' => 2,
 * ];
 *
 * $b =
 * [
 *     'users' =>
 *     [
 *         ['name' => 'Charlie'],
 *         ['name' => 'Alice'],
 *    ],
 *    'count' => null,
 *    'extra' => null,
 * ];
 *
 * // Deep merge, skip nulls, keep unique users, clean result
 * $result = merge( $a , $b ,
 * [
 *     MergeOption::DEEP   => true ,
 *     MergeOption::UNIQUE => true ,
 *     MergeOption::NULLS  => 'skip' ,
 *     MergeOption::CLEAN  => CleanFlag::DEFAULT
 * ]);
 *
 * // Result:
 * // [
 * //     'users' => [
 * //         ['name' => 'Alice'],
 * //         ['name' => 'Bob'],
 * //         ['name' => 'Charlie']
 * //     ],
 * //     'count' => 2
 * // ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function merge( array $original , array $external , array $options = [] ) :array
{
    $opts   = MergeOption::normalize( $options ) ;
    $merged = $original ;

    foreach ( $external as $key => $value )
    {
        if ( is_null( $value ) )
        {
            switch( $opts[ MergeOption::NULLS ] )
            {
                case NullsOption::SKIP :
                {
                    continue 2 ;
                }
                case NullsOption::KEEP :
                {
                    if ( array_key_exists( $key , $merged ) )
                    {
                        continue 2 ; // garder l'existant
                    }
                }
                case NullsOption::OVERWRITE :
                {
                    break ;
                }
            }
        }

        if
        (
            $opts[ MergeOption::DEEP ]
            && isset( $merged[ $key ] )
            && is_array( $merged[ $key ] )
            && is_array( $value )
        )
        {
            if ( isIndexed( $merged[ $key ] ) && isIndexed( $value ) )
            {
                /**
                 * Merge indexed arrays with optional uniqueness
                 */
                foreach ( $value as $v )
                {
                    if
                    (
                        !$opts[ MergeOption::UNIQUE ]
                        || !in_array( $v , $merged[ $key ] , true )
                    )
                    {
                        $merged[ $key ][] = $v ;
                    }
                }
            }
            else
            {
                /**
                 * Recursive merge for associative arrays
                 */
                $merged[ $key ] = merge
                (
                    $merged[ $key ] ,
                    $value ,
                    $opts
                ) ;
            }
        }
        /**
         * Indexed storage
         */
        else if ( $opts[ MergeOption::INDEXED ] )
        {
            if ( !isset( $merged[ $key ] ) || !is_array( $merged[ $key ] ) )
            {
                $merged[ $key ] = [] ;
            }

            $merged[ $key ][] = $value ;
        }
        /**
         * Numeric keys with optional uniqueness
         */
        else if( is_int( $key ) && isset( $merged[ $key ] ) && $opts[ MergeOption::PRESERVE_KEYS ] )
        {
            if ( $opts[ MergeOption::UNIQUE ] && in_array( $value , $merged , true ) )
            {
                continue ;
            }

            $merged[] = $value ;
        }
        /**
         * Default overwrite
         */
        else
        {
            $merged[ $key ] = $value ;
        }
    }

    if ( !is_null( $opts[ MergeOption::CLEAN ] ) )
    {
        $merged = clean( $merged , $opts[ MergeOption::CLEAN ] ) ?? [] ;
    }

    return $merged ;
}