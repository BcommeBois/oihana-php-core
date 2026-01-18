<?php

namespace oihana\core\arrays ;

/**
 * Prepends keys/values to the beginning of an array.
 *
 * @param array $array The target array.
 * @param array $before Keys/values to prepend.
 *
 * @return array The merged array with $before keys first.
 *
 * @example
 * ```php
 * $data = ['name' => 'Alice'];
 * $result = prepend( $data , ['id' => 'A']);
 * // [ 'id' => 'A' , 'name' => 'Alice' ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function prepend( array $array , array $before ) :array
{
    return $before + $array ;
}