<?php

namespace oihana\core\arrays ;

/**
 * Determines if an array is indexed.
 * <p>Usage :</p>
 * <pre>
 * use function core\arrays\isIndexed ;
 * $array = [ 'a', 'b' , 'c' ] ;
 * echo json_encode( isIndexed( $array ) ) ;
 * @param array $array The array to evaluate.
 * @return bool Indicates if the array is indexed or not.
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