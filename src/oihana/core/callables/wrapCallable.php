<?php

namespace oihana\core\callables;

/**
 * Wraps a callable to apply middleware/decorators before/after execution.
 *
 * This function allows you to decorate a callable with custom logic that executes
 * before and/or after the original callable is invoked. The wrapper receives the
 * original callable and its arguments, giving complete control over execution flow.
 *
 * The wrapper callable receives:
 * - First argument: The original callable to invoke
 * - Remaining arguments: The arguments passed to the wrapped callable
 *
 * The wrapper is responsible for calling the original callable and returning its result.
 * This enables use cases like logging, timing, error handling, caching, etc.
 *
 * @param callable $callable The original callable to wrap
 * @param callable $wrapper A callable that receives the original callable and args.
 *                          Signature: `function($original, ...$args)`
 *
 * @return callable A new wrapped callable that applies the wrapper logic
 *
 * @example
 * ```php
 * use function oihana\core\callables\wrapCallable;
 *
 * // Example 1: Logging wrapper
 * $fn = fn($x) => $x * 2;
 * $logged = wrapCallable($fn, function($original, ...$args)
 * {
 *     echo "Calling with: " . json_encode($args);
 *     $result = $original(...$args);
 *     echo "Result: $result";
 *     return $result;
 * });
 * $logged(5); // Logs: Calling with: [5], Result: 10
 *
 * // Example 2: Error handling wrapper
 * $wrapped = wrapCallable('json_decode', function( $original , ...$args )
 * {
 *     try
 *     {
 *         return $original(...$args) ;
 *     }
 *     catch (Throwable $e)
 *     {
 *         return null ;
 *     }
 * });
 *
 * // Example 3: Timing wrapper
 * $timed = wrapCallable( $expensiveFn , function( $original , ...$args )
 * {
 *     $start    = microtime(true);
 *     $result   = $original(...$args);
 *     $duration = microtime(true) - $start;
 *     error_log( "Execution took {$duration}s" ) ;
 *     return $result;
 * });
 * ```
 *
 * @see chainCallables() To combine multiple callables in sequence
 *
 * @package oihana\core\callables
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function wrapCallable( callable $callable , callable $wrapper ): callable
{
    return fn( ...$args ) => $wrapper( $callable , ...$args ) ;
}