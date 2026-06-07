<?php

namespace oihana\core\strings ;

/**
 * Converts a string to PascalCase (a.k.a. UpperCamelCase).
 *
 * Builds on {@see camel()} and then upper-cases the first character via {@see ucFirst()}.
 * Returns an empty string when the source is `null` or empty.
 *
 * @param ?string $source     The input string to convert.
 * @param array   $separators Separator characters split into words before conversion. Defaults to `["_", "-", "/"]`.
 *
 * @return string The PascalCase representation of the input string.
 *
 * @example
 * ```php
 * use function oihana\core\strings\pascal;
 *
 * echo pascal( 'hello_world' ) ; // "HelloWorld"
 * echo pascal( 'foo-bar_baz' ) ; // "FooBarBaz"
 * echo pascal( 'user/name' )   ; // "UserName"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function pascal( ?string $source , array $separators = [ "_" , "-" , "/" ] ): string
{
    return ucFirst( camel( $source , $separators ) ) ;
}
