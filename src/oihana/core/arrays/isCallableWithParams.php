<?php

namespace oihana\core\arrays;

/**
 * Check if an array represents a callable with parameters in simplified syntax.
 *
 * Distinguishes between:
 * - ['substring', 0, 3]        → callable with params (true)
 * - ['trim', 'lower']          → chain of callables (false)
 * - [['trim', 1], 'lower']     → chain of callables (false)
 * - ['lower']                  → single callable without params (false)
 *
 * This is useful for supporting simplified syntax in configurations:
 * - Simplified: ['functionName', param1, param2]
 * - Explicit:   [['functionName', param1, param2]]
 *
 * ### Detection Rules:
 * 1. Must have at least 2 elements (callable name + at least one param)
 * 2. First element must be a string (callable name)
 * 3. Optionally validate against a list of known callable names
 * 4. If second element is also a known callable name → it's a chain (false)
 * 5. If second element is an array → it's a chain (false)
 * 6. Otherwise → it's a callable with params (true)
 *
 * @param array      $array      Array to check
 * @param array|null $validNames Optional list of valid callable names to validate against.
 *                                If provided, both the first element and second element
 *                                (if string) will be checked against this list.
 *
 * @return bool True if it's a single callable with parameters
 *
 * @example
 * ```php
 * use function oihana\core\arrays\isCallableWithParams;
 *
 * // Without validation
 * isCallableWithParams(['substring', 0, 3]);
 * // Returns: true
 *
 * // With validation
 * $validFunctions = ['trim', 'lower', 'upper', 'substring'];
 *
 * isCallableWithParams(['substring', 0, 3], $validFunctions);
 * // Returns: true (substring is valid, params provided)
 *
 * isCallableWithParams(['trim', 'lower'], $validFunctions);
 * // Returns: false (both are valid function names → chain)
 *
 * isCallableWithParams(['unknown', 0, 3], $validFunctions);
 * // Returns: false (unknown is not in valid list)
 *
 * isCallableWithParams(['lower'], $validFunctions);
 * // Returns: false (no params provided)
 *
 * // Real-world usage
 * $config = ['cache', 'redis', 3600];
 * if (isCallableWithParams($config, ['cache', 'log', 'queue']))
 * {
 *     [$method, ...$params] = $config;
 *     $this->$method(...$params);
 * }
 * ```
 *
 * @author  Marc Alcaraz (eKameleon)
 * @package oihana\core\arrays
 * @version 1.0.8
 */
function isCallableWithParams( array $array , ?array $validNames = null ): bool
{
    // Must have at least 2 elements (callable name + at least one param)
    if ( count( $array ) < 2 )
    {
        return false;
    }

    // First element must be a string (callable name)
    if ( !is_string( $array[0] ) )
    {
        return false;
    }

    $callableName = $array[0];

    // Validate first element against known names if provided
    if ( $validNames !== null && !in_array( $callableName , $validNames , true ) )
    {
        return false;
    }

    // If second element is a string AND is a valid callable name → it's a chain
    if ( is_string( $array[1] ) )
    {
        if ( $validNames !== null && in_array( $array[1] , $validNames , true ) )
        {
            return false; // Chain: ['trim', 'lower']
        }
    }

    // If second element is an array → it's a chain with explicit format
    if ( is_array( $array[1] ) )
    {
        return false ; // Chain: [['trim', 1], 'lower']
    }

    // Otherwise → it's a callable with params
    return true ;
}