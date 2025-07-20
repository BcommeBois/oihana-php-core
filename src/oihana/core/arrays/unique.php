<?php

namespace oihana\core\arrays ;

/**
 * Removes duplicate values from an array and reindexes it.
 *
 * Combines the functionality of {@see array_unique()} and {@see array_values()}:
 * first removes duplicates, then reindexes the array numerically starting at 0.
 *
 * The optional `$flags` parameter modifies the comparison behavior, possible values:
 * - SORT_REGULAR: Compare items normally (don't change types).
 * - SORT_NUMERIC: Compare items numerically.
 * - SORT_STRING: Compare items as strings.
 * - SORT_LOCALE_STRING: Compare items as strings, based on the current locale.
 *
 * @param array $array The input array.
 * @param int $flags [optional] Comparison behavior flag. Default is SORT_STRING.
 *
 * @return array The filtered array with unique values, reindexed from 0.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\unique;
 *
 * $array = ['a', 'b', 'a', 'c', 'b'];
 * $result = unique($array);
 * print_r($result); // ['a', 'b', 'c']
 *
 * $numericArray = [1, 2, '1', 3];
 * $resultNumeric = unique($numericArray, SORT_NUMERIC);
 * print_r($resultNumeric); // [1, 2, 3]
 * ```
 *
 * @link https://www.php.net/manual/en/function.array-unique.php
 * @link https://www.php.net/manual/en/function.array-values.php
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function unique( array $array , int $flags = SORT_STRING ) :array
{
    return array_values( array_unique( $array , $flags ) ) ;
}