<?php

namespace oihana\core\callables;

/**
 * Chains multiple callables to be executed in sequence.
 *
 * Creates a pipeline where each callable's output becomes the next callable's input.
 * The first callable receives the arguments passed to the chain; subsequent callables
 * receive only the result from the previous callable.
 *
 * Returns null if the array is empty or if any callable cannot be resolved.
 * Execution stops at the first unresolvable callable in the chain.
 *
 * @param array $callables An array of callables to execute in sequence.
 *                          Each can be a string, array, object, or Closure.
 *
 * @return callable|null A new callable that executes the chain, or null if invalid
 *
 * @example
 * ```php
 * use function oihana\core\callables\chainCallables;
 *
 * // Example 1: Data transformation pipeline
 * $pipe = chainCallables([
 *     'trim',
 *     'strtoupper',
 *     fn($str) => str_repeat($str, 2)
 * ]);
 * echo $pipe('  hello  '); // "HELLOHELLO"
 *
 * // Example 2: Mathematical operations
 * $calculate = chainCallables([
 *     fn($x) => $x * 2,
 *     fn($x) => $x + 10,
 *     fn($x) => sqrt($x)
 * ]);
 * echo $calculate(5); // sqrt((5*2)+10) = sqrt(20) ≈ 4.47
 *
 * // Example 3: With static methods
 * $pipe = chainCallables
 * ([
 *     'MyClass::parse',
 *     'MyClass::validate',
 *     'MyClass::transform'
 * ]);
 * $result = $pipe($input);
 *
 * // Example 4: With object methods
 * $handler = new Handler();
 * $pipe = chainCallables
 * ([
 *     [ $handler , 'preprocess'  ] ,
 *     [ $handler , 'process'     ] ,
 *     [ $handler , 'postprocess' ]
 * ]);
 * $result = $pipe($data);
 * ```
 *
 * @see wrapCallable() To add decorators around a callable
 *
 * @package oihana\core\callables
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function chainCallables( array $callables = [] ) : ?callable
{
    if ( empty( $callables ) )
    {
        return null ;
    }

    // Resolve all callables upfront to fail fast
    $resolved = [] ;
    foreach ( $callables as $callable )
    {
        $resolved[] = resolveCallable( $callable ) ;
        if ( $resolved[ count( $resolved ) - 1 ] === null )
        {
            return null ;
        }
    }

    return fn( $input ) => array_reduce
    (
        $resolved ,
        fn( $result , $callable ) => $callable( $result ) ,
        $input
    );
}