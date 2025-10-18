<?php

namespace oihana\core\arrays ;

use InvalidArgumentException;

/**
 * Cleans an array by removing unwanted values based on bitwise flags from the `CleanFlag` enum.
 *
 * This function allows flexible and recursive filtering of arrays.
 * Cleaning behavior is fully customizable via bitwise flags.
 *
 * ### Supported flags from `CleanFlag`:
 *
 * - `CleanFlag::NULLS`       : Removes `null` values.
 * - `CleanFlag::EMPTY`       : Removes empty strings (`''`).
 * - `CleanFlag::TRIM`        : Trims strings and treats whitespace-only strings as empty.
 * - `CleanFlag::EMPTY_ARR`   : Removes empty arrays (after recursive cleaning).
 * - `CleanFlag::RECURSIVE`   : Cleans nested arrays recursively.
 * - `CleanFlag::FALSY`       : Removes all PHP falsy values (`null`, `''`, `0`, `0.0`, `'0'`, `false`, `[]`).
 * - `CleanFlag::MAIN`        : Shortcut for enabling all the main flags: `NULLS | EMPTY | EMPTY_ARR | TRIM`.
 * - `CleanFlag::RETURN_NULL` : Returns null if the final array is empty.
 *
 * ### Default behavior
 *
 * Using `CleanFlag::DEFAULT` is equivalent to enabling:
 *
 * ```
 * CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM | CleanFlag::EMPTY_ARR | CleanFlag::RECURSIVE
 * ```
 *
 * That means by default, `clean()` removes:
 * - `null`
 * - empty strings
 * - strings containing only spaces/tabs/newlines
 * - empty arrays
 * - and also cleans nested arrays recursively.
 *
 * @param array $array The input array to clean. Nested arrays are processed only if `CleanFlag::RECURSIVE` is set.
 * @param int   $flags A bitmask of `CleanFlag` values. Defaults to `CleanFlag::DEFAULT`.
 *
 * @return ?array The filtered array:
 *   - **Associative arrays:** keys are preserved.
 *   - **Indexed arrays:** automatically reindex to remove numeric gaps.
 *   - null if the final array is empty and the CleanFlag::RETURN_NULL option is used.
 *
 * @example
 * **1. Basic cleaning: remove nulls and empty strings**
 * ```php
 * use oihana\core\arrays\{clean, CleanFlag};
 *
 * $data   = ['foo', '', null, 'bar'];
 * $result = clean($data, CleanFlag::NULLS | CleanFlag::EMPTY);
 * // ['foo', 'bar']
 * ```
 *
 * **2. Remove whitespace-only strings as well**
 * ```php
 * $data   = ['foo', '   ', '', null, 'bar'];
 * $result = clean($data, CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM);
 * // ['foo', 'bar']
 * ```
 *
 * **3. Recursive cleaning of nested arrays**
 * ```php
 * $data = [
 *     'users' => [
 *         ['name' => '', 'email' => 'bob@example.com'],
 *         ['name' => 'Alice', 'email' => '']
 *     ]
 * ];
 *
 * $result = clean($data, CleanFlag::RECURSIVE | CleanFlag::EMPTY);
 * // [
 * //     'users' => [
 * //         ['email' => 'bob@example.com'],
 * //         ['name' => 'Alice']
 * //     ]
 * // ]
 * ```
 *
 * **4. Remove empty arrays after recursive cleaning**
 * ```php
 * $data = [
 *     'group1' => [],
 *     'group2' => [['name' => 'Alice'], []]
 * ];
 *
 * $result = clean($data, CleanFlag::RECURSIVE | CleanFlag::EMPTY_ARR);
 * // [
 * //     'group2' => [['name' => 'Alice']]
 * // ]
 * ```
 *
 * **5. Remove all falsy values at once**
 * ```php
 * $data   = [0, '', null, false, 'ok', [], '0'];
 * $result = clean($data, CleanFlag::FALSY);
 * // ['ok']
 * ```
 *
 * **6. Full cleaning using default flags**
 * ```php
 * $result = clean($data);
 * // Equivalent to CleanFlag::DEFAULT
 * ```
 *
 **7. Return null if the cleaned array is empty**
 * ```php
 * $data = ['', null, '   '];
 * $result = clean($data, CleanFlag::DEFAULT | CleanFlag::RETURN_NULL);
 * // null (instead of [])
 *
 * // Useful for validation
 * $cleaned = clean($userInput, CleanFlag::DEFAULT | CleanFlag::RETURN_NULL);
 * if ($cleaned === null) {
 *     throw new Exception('No valid data provided');
 * }
 * ```
 *
 * @see CleanFlag For available cleaning flags and default behavior.
 * @see isIndexed() To determine if an array is numerically indexed.
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz
 * @since   1.0.6
 */
function clean( array $array = [] , int $flags = CleanFlag::DEFAULT ): ?array
{
    if ( ! CleanFlag::isValid( $flags ) )
    {
        throw new InvalidArgumentException
        (
            sprintf
            (
                'Invalid flags provided. Expected valid CleanFlag constants, got: %s. Valid flags: %s' ,
                $flags ,
                CleanFlag::describe( CleanFlag::ALL )
            )
        );
    }

    if ( empty( $array ) )
    {
        return [] ;
    }

    $checkNulls     = ( $flags & CleanFlag::NULLS     ) !== 0 ;
    $checkEmpty     = ( $flags & CleanFlag::EMPTY     ) !== 0 ;
    $checkTrim      = ( $flags & CleanFlag::TRIM      ) !== 0 ;
    $checkEmptyArr  = ( $flags & CleanFlag::EMPTY_ARR ) !== 0 ;
    $checkRecursive = ( $flags & CleanFlag::RECURSIVE ) !== 0 ;
    $checkFalsy     = ( $flags & CleanFlag::FALSY     ) !== 0 ;

    $isIndexed = array_is_list( $array ) ;

    $result = [];

    foreach ( $array as $key => $value )
    {
        $keep = true;

        if ( is_array( $value ) )
        {
            if ( $checkRecursive )
            {
                $value = clean( $value , $flags ) ;
            }
            if ( $checkEmptyArr && empty( $value ) )
            {
                $keep = false ;
            }
        }
        else
        {
            if ( $checkFalsy )
            {
                $keep = (bool) $value ;
            }
            else
            {
                if ( $checkNulls && is_null( $value ) )
                {
                    $keep = false ;
                }
                else if ( is_string( $value ) && $checkEmpty )
                {
                    if ( $checkTrim )
                    {
                        $keep = trim( $value ) !== '' ;
                    }
                    else
                    {
                        $keep = $value !== '' ;
                    }
                }
            }
        }

        if ( $keep )
        {
            if ( $isIndexed )
            {
                $result[] = $value ;
            }
            else
            {
                $result[ $key ] = $value ;
            }
        }
    }

    if ( empty( $result ) && ( $flags & CleanFlag::RETURN_NULL ) !== 0 )
    {
        return null ;
    }

    return $result ;
}