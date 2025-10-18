<?php

namespace oihana\core\callables;


/**
 * Wrap a callable with before/after middleware.
 *
 * This function allows decorating a callable with logic that runs
 * before and/or after the original callable. Supports a single callable
 * or an array of callables for both `before` and `after`.
 *
 * - `before` callables receive the original arguments.
 * - `after` callables receive the original arguments + the result.
 *   If an after callback returns a non-null value, it overrides the result.
 *
 * @param callable            $callable The main callable to wrap
 * @param callable|callable[] $before   Callable or array of callables to run before
 * @param callable|callable[] $after    Callable or array of callables to run after
 *
 * @return callable Wrapped callable with middleware applied
 *
 * @example
 * ```php
 * use function oihana\core\callables\middlewareCallable;
 *
 * $fn = fn(int $x): int => $x * 2;
 *
 * // Single before and after callables
 * $wrapped = middlewareCallable(
 *     $fn,
 *     before: fn(int $x) => print("Before $x\n"),
 *     after:  fn(int $x, int $result) => print("After $result\n")
 * );
 *
 * echo $wrapped(5); // Before 5, After 10, 10
 *
 * // Multiple before/after callables
 * $wrapped2 = middlewareCallable(
 *     $fn,
 *     before: [
 *         fn(int $x) => print("B1-$x\n"),
 *         fn(int $x) => print("B2-$x\n")
 *     ],
 *     after: [
 *         fn(int $x, int $r) => print("A1-$r\n"),
 *         fn(int $x, int $r) => print("A2-$r\n")
 *     ]
 * );
 *
 * echo $wrapped2(3); // B1-3, B2-3, A1-6, A2-6, 6
 * ```
 *
 * @package oihana\core\callables
 * @author Marc Alcaraz
 * @since 1.0.7
 */
function middlewareCallable
(
    callable       $callable ,
    callable|array $before   = [] ,
    callable|array $after    = []
)
: callable
{
    $before = is_callable( $before ) ? [ $before ] : (array) $before ;
    $after  = is_callable( $after  ) ? [ $after  ] : (array) $after  ;

    return function ( ...$args ) use ( $callable , $before , $after )
    {
        // Execute before callbacks
        foreach ( $before as $b )
        {
            $b( ...$args ) ;
        }

        // Execute main callable
        $result = $callable( ...$args ) ;

        // Execute after callbacks
        foreach ( $after as $a )
        {
            $r = $a( ...array_merge( $args , [ $result ] ) ) ;
            if ( $r !== null )
            {
                $result = $r ;
            }
        }

        return $result;
    };
}