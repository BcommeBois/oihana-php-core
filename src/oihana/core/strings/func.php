<?php

namespace oihana\core\strings ;

/**
 * Generates a function expression like `NAME(arg1,arg2)`.
 *
 * @param string $name      The function name.
 * @param mixed  $arguments The arguments for the function.
 * @param string $separator The separator between arguments (default: Char::COMMA).
 *
 * @return string The function expression.
 *
 * @example
 * ```php
 * func('CALL', ['a', 'b']); // 'CALL(a,b)'
 * ```
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function func( string $name , mixed $arguments = null , string $separator = ',' ) :string
{
    return $name . betweenParentheses( expression: $arguments , separator: $separator , trim: false ) ;
}