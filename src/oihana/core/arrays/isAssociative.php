<?php

namespace oihana\core\arrays ;

/**
 * Determines if an array is associative.
 * <p>Usage :</p>
 * <pre>
 * use function core\arrays\isAssociative ;
 * $array =
 * [
 *   'id'          => 1 ,
 *   'created'     => null ,
 *   'name'        => 'hello world' ,
 *   'description' => null
 * ];
 * echo json_encode( isAssociative( $array ) ) ;
 * @param array $array The array to evaluate.
 * @return bool Indicates if the array is associative.
 */
function isAssociative( array $array ): bool
{
    $keys = array_keys( $array );
    return array_keys($keys) !== $keys;
}