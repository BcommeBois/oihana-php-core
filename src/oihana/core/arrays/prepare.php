<?php

namespace oihana\core\arrays ;

use oihana\core\options\ArrayOption;

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
    $options = ArrayOption::normalize( $options ) ;

    // Step 1: REDUCE
    if ( !empty( $options[ ArrayOption::REDUCE ] ) )
    {
        $array = reduce( $array , $options[ ArrayOption::REDUCE ] ) ;
    }

    // Step 2: BEFORE
    if ( !empty( $options[ ArrayOption::BEFORE ] ) )
    {
        $array = prepend( $array , $options[ ArrayOption::BEFORE ] ) ;
    }

    // Step 3: AFTER
    if ( !empty( $options[ ArrayOption::AFTER ] ) )
    {
        $array = append( $array , $options[ ArrayOption::AFTER ] ) ;
    }

    // Step 4: FIRST_KEYS + SORT
    if ( !empty( $options[ ArrayOption::FIRST_KEYS ] ) || !empty( $options[ ArrayOption::SORT ] ) )
    {
        $array = reorder
        (
            $array ,
            $options[ ArrayOption::FIRST_KEYS ] ?? [],
            $options[ ArrayOption::SORT ] ?? true
        );
    }

    return $array ;
}