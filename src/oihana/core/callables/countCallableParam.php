<?php

namespace oihana\core\callables;

use Closure;
use InvalidArgumentException;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use WeakMap;

/**
 * Returns the number of parameters of a given callable.
 *
 * This function accepts any PHP callable (Closure, invokable object, array [object/class, method],
 * string function name, or static method string) and returns the number of parameters it declares.
 *
 * Optionally, a cache can be used to avoid repeated reflection lookups for the same callable,
 * which is useful when the callable is repeatedly inspected (e.g., in loops or recursive functions).
 *
 * The cache never relies on the identifier of an object, which PHP hands to the next object once
 * the first is freed — a new closure would otherwise inherit the count of an old one. A closure is
 * kept in a `WeakMap`, whose entry goes with the closure ; any other callable is kept under its
 * name — the function, or `Class::method` (`Class::__invoke` for an invokable object), since the
 * count belongs to the method, not to an instance.
 *
 * @param callable|string|array<int, object|string>|object $callable The callable to inspect.
 * @param bool $useCache Whether to cache the computed parameter count. Default true.
 *
 * @return int The number of parameters declared by the callable.
 *
 * @throws InvalidArgumentException If the callable cannot be resolved or is unsupported.
 * @throws ReflectionException      If the callable cannot be reflected (rare).
 *
 * @example Basic usage with a named function
 * ```php
 * function testFunction($a, $b, $c) {}
 * echo countCallableParam('testFunction'); // 3
 * ```
 *
 * @example With a Closure
 * ```php
 * $fn = function($x, $y) {};
 * echo countCallableParam($fn); // 2
 * ```
 *
 * @example With an invokable object
 * ```php
 * class MyCallable { public function __invoke($a, $b, $c, $d) {} }
 * $obj = new MyCallable();
 * echo countCallableParam($obj); // 4
 * ```
 *
 * @example With a static method
 * ```php
 * class MyClass { public static function myMethod($x, $y) {} }
 * echo countCallableParam('MyClass::myMethod'); // 2
 * ```
 *
 * @example With an object method
 * ```php
 * class MyClass { public function myMethod($x, $y, $z) {} }
 * $obj = new MyClass();
 * echo countCallableParam([$obj, 'myMethod']); // 3
 * ```
 *
 * @package oihana\core\callables
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function countCallableParam
(
    callable|string|array|object $callable ,
    bool                         $useCache = true
)
:int
{
    /** @var array<string, int> $countsByName */
    static $countsByName = [] ;

    /** @var WeakMap<Closure, int>|null $countsByClosure */
    static $countsByClosure = null ;

    $resolved = resolveCallable( $callable );

    if ( $resolved === null )
    {
        throw new InvalidArgumentException('Cannot resolve callable' ) ;
    }

    $countsByClosure ??= new WeakMap() ;

    // A closure is kept by itself (null name), any other callable by its name.
    $name = match( true )
    {
        $resolved instanceof Closure => null ,
        is_string( $resolved )       => $resolved ,
        is_array( $resolved )        => ( is_object( $resolved[0] ) ? $resolved[0]::class : $resolved[0] ) . '::' . $resolved[1] ,
        is_object( $resolved )       => $resolved::class . '::__invoke' ,
        // defensive: resolveCallable() returns a closure, a string, an array or an invokable object
        // @codeCoverageIgnoreStart
        default                      => throw new InvalidArgumentException('Unsupported callable type' ) ,
        // @codeCoverageIgnoreEnd
    } ;

    if ( $useCache )
    {
        $cached = $name === null ? ( $countsByClosure[ $resolved ] ?? null ) : ( $countsByName[ $name ] ?? null ) ;

        if ( is_int( $cached ) )
        {
            return $cached ;
        }
    }

    if ( $resolved instanceof Closure || is_string( $resolved ) )
    {
        if ( is_string($resolved) && str_contains($resolved, '::' ) )
        {
            [ $class , $method ] = explode('::' , $resolved , 2 ) ;
            $reflection = new ReflectionMethod( $class , $method ) ;
        }
        else
        {
            $reflection = new ReflectionFunction( $resolved ) ;
        }
    }
    else if ( is_object( $resolved ) && method_exists( $resolved , '__invoke' ) )
    {
        $reflection = new ReflectionMethod( $resolved , '__invoke' ) ;
    }
    else if ( is_array( $resolved ) )
    {
        $reflection = new ReflectionMethod( $resolved[0] ,  $resolved[1] ) ;
    }
    // defensive: resolveCallable() already rejected unsupported types
    // @codeCoverageIgnoreStart
    else
    {
        throw new InvalidArgumentException('Unsupported callable type' ) ;
    }
    // @codeCoverageIgnoreEnd

    $count = $reflection->getNumberOfParameters() ;

    if ( $useCache )
    {
        if ( $name === null )
        {
            $countsByClosure[ $resolved ] = $count ;
        }
        else
        {
            $countsByName[ $name ] = $count ;
        }
    }

    return $count ;
}