<?php

namespace oihana\core\arrays ;

use oihana\core\options\PrepareOption;

/**
 * Prepares an array according to the given options:
 * - REDUCE   : filter/compress values
 * - BEFORE   : prepend keys
 * - AFTER    : append keys
 * - FIRST_KEYS + SORT : reorder keys
 *
 * This is typically used before serializing arrays for JSON output.
 *
 * @param array $array   The input array.
 * @param array $options Options for transformation:
 *                       - 'reduce'      => bool|array|callable
 *                       - 'before'      => array
 *                       - 'after'       => array
 *                       - 'first_keys'  => array
 *                       - 'sort'        => bool
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function prepare( array $array , array $options = [] ) :array
{
    $options = PrepareOption::normalize( $options ) ;

    // Step 1: REDUCE
    if ( !empty( $options[ PrepareOption::REDUCE ] ) )
    {
        $array = reduce( $array , $options[ PrepareOption::REDUCE ] ) ;
    }

    // Step 2: BEFORE
    if ( !empty( $options[ PrepareOption::BEFORE ] ) )
    {
        $array = prepend( $array , $options[ PrepareOption::BEFORE ] ) ;
    }

    // Step 3: AFTER
    if ( !empty( $options[ PrepareOption::AFTER ] ) )
    {
        $array = append( $array , $options[ PrepareOption::AFTER ] ) ;
    }

    // Step 4: FIRST_KEYS + SORT
    if ( !empty( $options[ PrepareOption::FIRST_KEYS ] ) || !empty( $options[ PrepareOption::SORT ] ) )
    {
        $array = reorder
        (
            $array ,
            $options[ PrepareOption::FIRST_KEYS ] ?? [],
            $options[ PrepareOption::SORT ] ?? true
        );
    }

    return $array ;
}