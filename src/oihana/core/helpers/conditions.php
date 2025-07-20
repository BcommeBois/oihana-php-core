<?php

namespace oihana\core\helpers ;

use InvalidArgumentException;

/**
 * Normalizes conditions into an array of callable functions.
 *
 * This function takes a variety of input types (null, callable, or array of callables)
 * and returns a normalized array of callable conditions. It is useful for validating
 * or filtering data based on multiple conditions.
 *
 * @param array|callable|string|null $conditions The conditions to normalize. Can be:
 *  - null: defaults to a condition that checks for null values.
 *  - callable: a single condition function.
 *  - array: an array of condition functions.
 * @param bool $throwable If true, throws exceptions for invalid conditions. If false, silently filters out invalid conditions.
 *
 * @return array An array of callable conditions.
 *
 * @example
 * Basic usage with default condition
 * ```php
 * $conditions = conditions();
 * // Returns: [fn($value) => is_null($value)]
 * ```
 *
 * Usage with a single callable condition
 * ```php
 * $conditions = conditions(fn($value) => $value === '');
 * // Returns: [fn($value) => $value === '']
 * ```
 *
 * Usage with an array of conditions
 * ```php
 * $conditions = conditions
 * ([
 *    fn($value) => $value === null,
 *    fn($value) => $value === false
 * ]);
 * // Returns: [fn($value) => $value === null, fn($value) => $value === false]
 * ```
 *
 * Usage with non-callable in array and throwable=false
 * ```php
 * $conditions = conditions([fn($value) => true, 'not_callable'], false);
 * // Returns: [fn($value) => true]
 * ```
 *
 * @package oihana\core\conditions
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function conditions( array|callable|string|null $conditions = null , bool $throwable = false ): array
{
    if ( $conditions === null )
    {
        return [ fn( $value ) => is_null( $value ) ] ;
    }
    elseif ( is_callable( $conditions ) )
    {
        return [ $conditions ] ;
    }
    elseif ( is_array( $conditions ) )
    {
        return array_filter( $conditions , function ( $condition ) use ( $throwable )
        {
            if ( !is_callable( $condition ) )
            {
                if ( $throwable )
                {
                    throw new InvalidArgumentException("All conditions in the array must be callable.");
                }
                return false ;
            }
            return true ;
        });
    }
    else if ( $throwable )
    {
        throw new InvalidArgumentException("The condition must be callable and array or null");
    }
    return [] ;
}