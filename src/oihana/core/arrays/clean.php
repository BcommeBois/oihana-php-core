<?php

namespace oihana\core\arrays ;

/**
 * Cleans an array by removing unwanted values such as `null` and empty strings.
 *
 * This function preserves the array's original keys for associative arrays,
 * and reindex numeric arrays automatically. Useful for filtering user input,
 * form submissions, or any array that may contain empty or null values.
 *
 * @param array $array The input array to clean. Defaults to an empty array.
 *
 * @return array The filtered array:
 *   - Keys are preserved for associative arrays.
 *   - Numeric arrays are reindex to remove gaps caused by removed values.
 *
 * @example
 * ```php
 * clean(['foo', '', null, 'bar']);
 * // Returns: ['foo', 'bar']
 *
 * clean(['id' => 1, 'name' => '', 'email' => null]);
 * // Returns: ['id' => 1]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function clean( array $array = [] ):array
{
    $indexed  = isIndexed( $array ) ;
    $filtered = array_filter( $array , fn( $value ) => !is_null( $value ) && $value !== '' ) ;
    return $indexed ? array_values($filtered) : $filtered;
}