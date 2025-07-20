<?php

namespace oihana\core\arrays ;

/**
 * Determines if an array is indexed (i.e., has sequential integer keys starting at 0).
 *
 * An array is considered indexed if its keys are exactly the sequence 0, 1, 2, ... n-1,
 * where n is the number of elements in the array.
 * This is typical for arrays used as lists.
 *
 * Empty arrays are considered indexed by definition.
 *
 * @param array $array The array to evaluate.
 *
 * @return bool True if the array is indexed, false otherwise.
 *
 * @example
 * ```php
 * use function core\arrays\isIndexed ;
 *
 * $array = [ 'a', 'b' , 'c' ] ;
 * echo json_encode( isIndexed( $array ) ) ;
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isIndexed( array $array ): bool
{
    if ( empty( $array ) )
    {
        return true ; // by default an empty array is indexed
    }

    $keys = array_keys( $array );
    return array_keys( $keys ) === $keys ;
}