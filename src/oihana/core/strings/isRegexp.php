<?php

namespace oihana\core\strings ;

/**
 * Determines whether a given string is a valid regular expression pattern.
 *
 * This function checks if the string can be safely used in a `preg_match()` call.
 * It does not validate semantic correctness (e.g., useless patterns), only syntax.
 *
 * ⚠️ Internally uses the `@` operator to suppress warnings from `preg_match()`.
 *
 * @param string $pattern The string to test as a potential regex pattern.
 * @return bool `true` if the string is a valid PCRE regular expression, `false` otherwise.
 *
 * @example
 * ```php
 * isRegexp('/^[a-z]+$/');       // true
 * isRegexp('/[0-9]{3,}/');      // true
 * isRegexp('not a regex');      // false (missing delimiters)
 * isRegexp('/unterminated');    // false
 * ```
 */
function isRegexp( string $pattern ): bool
{
    // Use the @ operator to suppress PHP warnings when the regex is invalid
    return @preg_match( $pattern , '' ) !== false ;
}