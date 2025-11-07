<?php

namespace oihana\core\callables;

/**
 * Defines the possible types of a callable reference.
 *
 * This class provides constants that can be used to identify the type
 * of a callable in a standardized way. These types are returned by
 * {@see callableType()} and can be used for validation or normalization
 * of callables in your code.
 *
 * Callable types:
 *
 * - {@see CLOSURE}   : A Closure (anonymous function or arrow function).
 * - {@see FUNCTION}  : A plain named function.
 * - {@see INVOCABLE} : An object implementing __invoke().
 * - {@see STATIC}    : A static class method (["ClassName", "method"] or "ClassName::method").
 * - {@see OBJECT}    : An object method ([$object, "method"]).
 * - {@see UNKNOWN}   : Any other callable type not recognized or normalized.
 *
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 * @package oihana\core\callables
 */
class CallableType
{
    /**
     * A Closure (anonymous function or arrow function).
     */
    public const string CLOSURE = 'closure' ;

    /**
     * A plain named function.
     */
    public const string FUNCTION = 'function' ;

    /**
     * An object implementing __invoke().
     */
    public const string INVOCABLE = 'invocable' ;

    /**
     * A static class method (["ClassName", "method"] or "ClassName::method").
     */
    public const string STATIC = 'static' ;

    /**
     * An object method ([$object, "method"]).
     */
    public const string OBJECT = 'object' ;

    /**
     * Any other callable type not recognized or normalized.
     */
    public const string UNKNOWN = 'unknown' ;
}