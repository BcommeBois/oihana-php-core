<?php

namespace oihana\core\arrays ;

/**
 * Determines if an array is associative.
 * <p>Usage :</p>
 * <pre>
 * ```php
 * use function core\arrays\isAssociative ;
 * $array =
 * [
 *   'id'          => 1 ,
 *   'created'     => null ,
 *   'name'        => 'hello world' ,
 *   'description' => null
 * ];
 * echo json_encode( isAssociative( $array ) ) ;
 * ```
 *
 * @param array $array The array to evaluate.
 * @return bool Indicates if the array is associative.
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isAssociative( array $array ): bool
{
    if ( empty( $array ) )
    {
        return false ; // by default an empty array is indexed
    }
    return array_keys($array) !== range(0, count($array) - 1);
}