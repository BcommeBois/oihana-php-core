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
 * @param array<int|string, mixed> $array The array to evaluate.
 * @return bool Indicates if the array is associative.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\isAssociative;
 *
 * isAssociative( [ 'a' => 1 ] ) ; // true
 * isAssociative( [ 1, 2, 3 ] ) ;  // false
 * isAssociative( [] ) ;           // false
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isAssociative( array $array ): bool
{
    return !array_is_list( $array ) ;
}