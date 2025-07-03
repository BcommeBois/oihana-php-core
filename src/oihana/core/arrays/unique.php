<?php

namespace oihana\core\arrays ;

/**
 * Removes duplicate values from an array and re-indexed it.
 * Combines the array_unique and array_values methods.
 * @param array $array The input array.
 * @param int $flags [optional]
 * <p>The optional second parameter sort_flags may be used to modify the sorting behavior using these values:</p>
 * <p>Sorting type flags:</p>
 * <ul>
 *   <li><b>SORT_REGULAR</b> - compare items normally (don't change types)</li>
 *   <li><b>SORT_NUMERIC</b> - compare items numerically</li>
 *   <li><b>SORT_STRING</b> - compare items as strings</li>
 *   <li><b>SORT_LOCALE_STRING</b> - compare items as strings, based on the current locale</li>
 * </ul>
 * @return array the filtered and re-indexed array.
 * @link https://php.net/manual/en/function.array-unique.php
 * @link https://php.net/manual/en/function.array-values.php
 */
function unique( array $array , int $flags = SORT_STRING ) :array
{
    return array_values( array_unique( $array , $flags ) ) ;
}