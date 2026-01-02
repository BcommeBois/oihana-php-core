<?php

namespace oihana\core\callables;

use Closure;

/**
 * Resolves a string callable into an actual callable.
 *
 * This function attempts to convert various callable representations into actual PHP callables
 * that can be invoked directly. It validates the existence of functions and methods before
 * returning them, ensuring the returned callable is executable.
 *
 * Supports multiple callable formats:
 * - Fully qualified function names: `'oihana\core\normalize'`
 * - Static method notation: `'MyClass::method'` or `'MyNamespace\MyClass::method'`
 * - Array callables: `[$object, 'method']` or `['ClassName', 'method']`
 * - Invokable objects: Objects implementing `__invoke` method
 * - Closure instances and callable objects
 *
 * Returns null if the callable cannot be resolved (function/method does not exist or format is invalid).
 *
 * @param string|array|object|null $callable The callable candidate to resolve. Can be:
 *                                             - A string containing a function or method name
 *                                             - An array with object/class and method name
 *                                             - An object instance with __invoke method
 *                                             - A Closure instance
 *                                             - null
 *
 * @return callable|null The resolved callable that can be executed, or null if the callable
 *                       cannot be resolved or does not exist in the current runtime.
 *
 * @example
 * ```php
 * use function oihana\core\callables\resolveCallable;
 *
 * // Function by fully qualified name
 * $fn = resolveCallable('oihana\core\normalize');
 * if ($fn !== null)
 * {
 *     $result = $fn($value);
 * }
 *
 * // Static method
 * $fn = resolveCallable('MyNamespace\MyClass::transform');
 * if ($fn !== null)
 * {
 *     $result = $fn($data);
 * }
 *
 * // Array callable with object instance
 * $handler = new MyHandler();
 * $fn = resolveCallable([$handler, 'handle']);
 *
 * // Invokable object
 * $fn = resolveCallable(new MyCallable());
 *
 * // Non-existent function returns null
 * $fn = resolveCallable('nonexistent\function'); // null
 * $fn = resolveCallable('NonExistentClass::method'); // null
 * ```
 *
 * @see is_callable() For direct callable validation without resolution
 *
 * @package oihana\core\callables
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function resolveCallable( string|array|object|null $callable ) :?callable
{
    // Handle null and Closure instances immediately
    if ( $callable === null || $callable instanceof Closure )
    {
        return $callable ;
    }

    // Handle invokable objects
    if ( is_object( $callable ) && method_exists( $callable , '__invoke' ) )
    {
        /** @var callable $callable */
        return $callable ;
    }

    // Handle array callables [$object, 'method'] or ['Class', 'method']
    if ( is_array( $callable ) )
    {
        return is_callable($callable) ? $callable : null;
    }

    // Handle string-based callables only
    if ( !is_string( $callable ) )
    {
        return null;
    }

    // Check for static method notation (Class::method)
    if ( str_contains( $callable , '::' ) )
    {
        return is_callable($callable) ? $callable : null;
    }

    // Handle global or namespaced functions
    return function_exists( $callable ) ? $callable : null ;
}