<?php

namespace oihana\core ;

/**
 * Checks whether a given value is a literal string representing a boolean (`true` or `false`) or `null`.
 *
 * This function returns `true` only if the value is a string and equals (case-insensitive)
 * to one of the three literal values: "true", "false", or "null".
 *
 * @param mixed $value The value to test.
 *
 * @return bool Returns true if the value is a string and matches "true", "false", or "null" (case-insensitive).
 *
 * @example
 * ```php
 * isLiteral('true');   // true
 * isLiteral('False');  // true
 * isLiteral('null');   // true
 * isLiteral('yes');    // false
 * isLiteral(true);     // false
 * isLiteral(null);     // false
 * ```
 *
 * @package oihana\core
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isLiteral( mixed $value ) :bool
{
    static $literals = [ 'true' , 'false' , 'null' ] ;
    return is_string( $value ) && in_array( strtolower( $value ) , $literals , true ) ;
}