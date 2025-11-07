<?php

namespace oihana\core\callables;

use Closure;
use ReflectionMethod;

/**
 * Determines the type of a callable reference and optionally normalizes it.
 *
 * This function analyzes a PHP callable and returns its standardized type according
 * to the constants defined in {@see CallableType}. It can also normalize the callable
 * form for consistent usage throughout your application.
 *
 * ## Callable Types and Normalization
 *
 * | Callable                        | Normalization                   | Returned Type |
 * |---------------------------------|---------------------------------|---------------|
 * | `function (...) {...}`          | `function (...) {...}`          | `'closure'`   |
 * | `$object` (with __invoke)       | `$object`                       | `'invocable'` |
 * | `"function"`                    | `"function"`                    | `'function'`  |
 * | `"Class::method"`               | `["Class", "method"]`           | `'static'`    |
 * | `["Class", "parent::method"]`   | `["ParentClass", "method"]`     | `'static'`    |
 * | `["Class", "self::method"]`     | `["Class", "method"]`           | `'static'`    |
 * | `["Class", "method"]`           | `["Class", "method"]`           | `'static'`    |
 * | `[$object, "parent::method"]`   | `[$object, "parent::method"]`   | `'object'`    |
 * | `[$object, "self::method"]`     | `[$object, "method"]`           | `'object'`    |
 * | `[$object, "method"]`           | `[$object, "method"]`           | `'object'`    |
 * | Other recognized callable       | Same as input                   | `'unknown'`   |
 * | Non-callable                    | Same as input                   | `false`       |
 *
 * ## Strict Mode
 *
 * When the `$strict` parameter is set to `true`, additional checks are performed
 * using the Reflection API:
 *
 * - For callable strings like `"Class::method"` or arrays like `["Class", "method"]`,
 *   the method **must** be declared as static.
 * - For callable arrays like `[$object, "method"]`, the method **must** be an
 *   instance method (non-static).
 *
 * In non-strict mode (`$strict = false`), these checks are skipped and the type
 * is determined solely by the callable's structure.
 *
 * ## Usage Examples
 *
 * ```php
 * // Closure
 * $type = getCallableType(fn() => 42, false, $norm);
 * // Returns: 'closure', $norm = the closure itself
 *
 * // Named function
 * $type = getCallableType('strlen', false, $norm);
 * // Returns: 'function', $norm = 'strlen'
 *
 * // Static method (string form)
 * $type = getCallableType('MyClass::myMethod', false, $norm);
 * // Returns: 'static', $norm = ['MyClass', 'myMethod']
 *
 * // Static method (array form)
 * $type = getCallableType(['MyClass', 'myMethod'], false, $norm);
 * // Returns: 'static', $norm = ['MyClass', 'myMethod']
 *
 * // Instance method
 * $obj = new MyClass();
 * $type = getCallableType([$obj, 'myMethod'], false, $norm);
 * // Returns: 'object', $norm = [$obj, 'myMethod']
 *
 * // Invocable object
 * $invocable = new class {
 *     public function __invoke() { return 'hello'; }
 * };
 * $type = getCallableType($invocable, false, $norm);
 * // Returns: 'invocable', $norm = $invocable
 *
 * // Non-callable
 * $type = getCallableType(123, false, $norm);
 * // Returns: false, $norm = 123
 * ```
 *
 * @param mixed $callable The callable reference to analyze.
 * @param bool $strict When `true`, uses Reflection to verify that static/instance
 *                     methods are used correctly. Defaults to `true`.
 * @param callable|null &$norm Receives the normalized form of the callable (passed by reference).
 *                             If the callable is invalid, contains the original value.
 *
 * @return string|false One of {@see CallableType::CLOSURE}, {@see CallableType::INVOCABLE},
 *                      {@see CallableType::FUNCTION}, {@see CallableType::STATIC},
 *                      {@see CallableType::OBJECT}, {@see CallableType::UNKNOWN},
 *                      or `false` if not a callable.
 *
 * @see CallableType For the list of type constants returned by this function
 * @see is_callable() For PHP's native callable verification function
 * @see ReflectionMethod For the Reflection API used in strict mode
 *
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 * @package oihana\core\callables
 */
function getCallableType( mixed $callable , bool $strict = true , ?callable & $norm = null ) :string|false
{
    // 1. Check if it's a Closure (anonymous function or arrow function)
    if ( $callable instanceof Closure )
    {
        $norm = $callable ;
        return CallableType::CLOSURE ;
    }

    // 2. Check if it's an invocable object (implementing __invoke)
    if ( is_object( $callable ) && is_callable( $callable ) )
    {
        $norm = $callable ;
        return CallableType::INVOCABLE ;
    }

    // 3. Check if it's an array [class/object, method]
    if ( is_array( $callable ) && isset( $callable[0] , $callable[1] ) )
    {
        [ $target, $method ] = $callable ;

        if ( method_exists( $target, $method ) )
        {
            $rm = new ReflectionMethod( $target, $method ) ;

            // Case: static method called on a class (string)
            if ( is_string( $target ) && ( ! $strict || $rm->isStatic() ) )
            {
                $norm = [ $target , $method ] ;
                return CallableType::STATIC ;
            }

            // Case: instance method called on an object
            if ( is_object( $target ) && ( ! $strict || ! $rm->isStatic() ) )
            {
                $norm = [ $target , $method ] ;
                return CallableType::OBJECT ;
            }
        }

        $norm = $callable ;
        return CallableType::UNKNOWN ;
    }

    // 4. Check if it's a string
    if ( is_string( $callable ) )
    {
        // First, check if it's an existing named function
        if ( function_exists( $callable ) )
        {
            $norm = $callable ;
            return CallableType::FUNCTION ;
        }

        // Then, check for "Class::method" format (including anonymous classes)
        if ( str_contains( $callable , '::' ) )
        {
            $parts = explode( '::' , $callable , 2 ) ;
            if ( count( $parts ) === 2 )
            {
                [ $class , $method ] = $parts ;

                if ( method_exists( $class , $method ) )
                {
                    $rm = new ReflectionMethod( $class , $method ) ;

                    // In non-strict mode OR if the method is static
                    if ( ! $strict || $rm->isStatic() )
                    {
                        $norm = [ $class , $method ] ;
                        return CallableType::STATIC ;
                    }
                }
            }
        }
    }

    $norm = $callable ;

    return is_callable( $callable ) ? CallableType::UNKNOWN : false ;
}