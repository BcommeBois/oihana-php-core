<?php

namespace oihana\core\arrays ;

/**
 * Cleans an array by removing unwanted values such as `null` and empty strings and arrays.
 *
 * This function works recursively:
 * - If an element is an array, it will also be cleaned of empty values.
 * - Associative array keys are preserved.
 * - Numeric arrays are automatically reindex after filtering.
 *
 * This is useful for:
 * - Filtering user input or form submissions.
 * - Removing empty entries from nested arrays.
 * - Sanitizing data before processing or storage.
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
 * clean(['id' => 1, 'name' => '', 'email' => null ]);
 * // Returns: ['id' => 1]
 *
 * clean(['users' => [['name' => 'Alice', 'email' => ''], ['name' => '', 'email' => 'bob@example.com']]]);
 * // Returns: ['users' => [['name' => 'Alice'], ['email' => 'bob@example.com']]]
 * ```
 *
 * @see isIndexed() For determining if an array is numerically indexed.
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function clean( array $array = [] ):array
{
    $indexed  = isIndexed( $array ) ;

    $filtered = array_filter
    (
        $array,
        function ( $value )
        {
            if ( is_array( $value ) )
            {
                $value = clean( $value ) ;
                return !empty( $value ) ;
            }
            return !is_null($value) && $value !== '';
        }
    );

    return $indexed ? array_values( $filtered ) : $filtered ;
}