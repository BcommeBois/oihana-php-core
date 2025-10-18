<?php

namespace oihana\core\callables;

/**
 * Resolves a string callable into an actual callable.
 *
 * Supports multiple callable formats:
 * - Fully qualified function names: `'oihana\core\normalize'`
 * - Static method notation: `'MyClass::method'` or `'MyNamespace\MyClass::method'`
 *
 * Returns null if the callable cannot be resolved (function/method does not exist).
 *
 * @param string $callable The callable as a string
 *
 * @return callable|null The resolved callable, or null if invalid or does not exist
 *
 * @example
 * ```php
 * use function oihana\core\callables\resolveCallable;
 *
 * // Function by fully qualified name
 * $fn = resolveCallable('oihana\core\normalize');
 * if ($fn !== null)
 * {
 *    $result = $fn($value);
 * }
 *
 * // Static method
 * $fn = resolveCallable('MyNamespace\MyClass::transform');
 *
 * // Non-existent function returns null
 * $fn = resolveCallable('nonexistent\function'); // null
 * ```
 *
 * @package oihana\core\callables
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function resolveCallable( string $callable ) :?callable
{
    if ( str_contains( $callable , '::' ) )
    {
        if ( is_callable( $callable ) )
        {
            return $callable ;
        }
        return null ;
    }

    return function_exists( $callable ) ? $callable : null ;
}