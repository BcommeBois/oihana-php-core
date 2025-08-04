<?php

namespace oihana\core\strings ;

/**
 * Converts a scalar value (string, boolean, float, etc.) to a simplified, human-readable PHP string representation.
 *
 * Useful when a less verbose and more natural output is desired, especially for inline dumps or generated code.
 * Supports custom quote style for strings ('single' or 'double') and optional compact escaping (control characters and trimming).
 *
 * - Strings are quoted and escaped appropriately using {@see formatQuotedString()}.
 * - Booleans are represented as `true` or `false`.
 * - Floats with no decimal part (e.g., 42.0) are simplified as integers (e.g., `42`).
 * - Null is represented as `null`.
 * - Other types (e.g. arrays, objects) fall back to `var_export()` representation.
 *
 * @param mixed  $value    The scalar value to convert.
 * @param string $quote    The quote style for strings: `'single'` or `'double'`. Default is `'single'`.
 * @param bool   $compact  If true, trims and escapes control characters in strings, even with single quotes.
 *
 * @return string The human-readable PHP representation of the scalar value.
 *
 * @example
 * ```php
 * echo toHumanReadableScalar("hello");                        // 'hello'
 * echo toHumanReadableScalar("He said: \"ok\"", 'double');   // "He said: \"ok\""
 * echo toHumanReadableScalar(true);                          // true
 * echo toHumanReadableScalar(false);                         // false
 * echo toHumanReadableScalar(42.0);                          // 42
 * echo toHumanReadableScalar(3.14);                          // 3.14
 * echo toHumanReadableScalar(null);                          // null
 * echo toHumanReadableScalar("Line\nBreak", 'single');       // 'Line
 *                                                             // Break'
 * echo toHumanReadableScalar("Line\nBreak", 'single', true); // 'Line\nBreak'
 * ```
 *
 * @see formatQuotedString()
 *
 * @package oihana\core\strings
 * @since 1.0.0
 * @author Marc Alcaraz
 */
function toHumanReadableScalar( mixed $value , string $quote = 'single' , bool $compact = false ): string
{
    return match ( gettype( $value ) )
    {
        'string'  => formatQuotedString( $value , $quote , $compact ) ,
        'boolean' => $value ? 'true' : 'false',
        'double'  => fmod( $value , 1 ) === 0.0 ? (string)(int) $value : (string) $value ,
        'NULL'    => 'null',
        default   => var_export( $value , true ) ,
    };
}