<?php

namespace oihana\core\helpers ;

use InvalidArgumentException;

/**
 * Normalize a set of conditions into an array of callable functions.
 *
 * This helper accepts multiple forms of input (null, single callable, array of callables)
 * and returns a consistent array of callable conditions. It is useful when you want
 * to filter, validate, or compress data using multiple dynamic rules.
 *
 * @param array|callable|string|null $conditions The conditions to normalize. Can be:
 *  - null: defaults to a single condition that checks for null values.
 *  - callable: a single condition function.
 *  - array: an array of callable functions.
 *  - string: (optional) a single callable function name or expression.
 * @param bool $throwable If true, invalid conditions will throw an InvalidArgumentException.
 *                        If false, invalid conditions are silently ignored.
 *
 * @return array<callable> Array of callable conditions.
 *
 * @throws InvalidArgumentException If $throwable=true and any provided condition is invalid.
 *
 * @example Default condition (null)
 * ```php
 * $conditions = conditions();
 * // Returns: [fn($value) => is_null($value)]
 * ```
 *
 * @example Single callable condition
 * ```php
 * $conditions = conditions(fn($v) => $v === '');
 * // Returns: [fn($v) => $v === '']
 * ```
 *
 * @example Array of conditions
 * ```php
 * $conditions = conditions([
 *     fn($v) => $v === null,
 *     fn($v) => $v === false
 * ]);
 * // Returns: [fn($v) => $v === null, fn($v) => $v === false]
 * ```
 *
 * @example Non-callable in array with throwable=false
 * ```php
 * $conditions = conditions([fn($v) => true, 'not_callable'], false);
 * // Returns: [fn($v) => true], 'not_callable' is ignored
 * ```
 *
 * @example Invalid condition with throwable=true
 * ```php
 * // Throws InvalidArgumentException
 * $conditions = conditions('not_a_callable', true);
 * ```
 *
 * @example Mixing callables and null
 * ```php
 * $conditions = conditions([null, fn($v) => is_numeric($v)], false);
 * // Returns: [fn($v) => is_numeric($v)]
 * ```
 *
 * @package oihana\core\helpers
 * @author  Marc Alcaraz
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