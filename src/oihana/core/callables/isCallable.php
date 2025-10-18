<?php

namespace oihana\core\callables;

/**
 * Checks whether a string can be resolved into a valid callable.
 *
 * This is a convenience wrapper around `resolveCallable()` that returns a boolean
 * instead of the callable itself. Useful for validation or conditional logic.
 *
 * Supports the same callable formats as `resolveCallable()`:
 * - Fully qualified function names: `'oihana\core\normalize'`
 * - Static method notation: `'MyClass::method'` or `'MyNamespace\MyClass::method'`
 *
 * @param string $callable The callable as a string to check
 *
 * @return bool True if the callable can be resolved, false otherwise
 *
 * @see resolveCallable()
 *
 * @example
 * ```php
 * use function oihana\core\callables\isCallable;
 *
 * if (isCallable('oihana\core\normalize')) {
 *     // Safe to use the function
 * }
 *
 * if (isCallable('MyClass::transform')) {
 *     // Safe to call the static method
 * }
 *
 * if (!isCallable('nonexistent\function')) {
 *     // Handle missing function
 * }
 * ```
 *
 * @package oihana\core\callables
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function isCallable( string $callable ): bool
{
    return resolveCallable( $callable ) !== null ;
}