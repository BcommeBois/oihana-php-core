<?php

namespace oihana\core\arrays ;

/**
 * Appends keys/values to the end of an array.
 *
 * @param array $array The target array.
 * @param array $after Keys/values to append.
 *
 * @return array The merged array with $after keys last.
 *
 * @example
 * ```php
 * $data = ['name' => 'Alice'];
 * $result = append($data, ['timestamp' => time()]);
 * // ['name' => 'Alice', 'timestamp' => 1234567890]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function append( array $array , array $after ) :array
{
    return $array + $after;
}