<?php

namespace oihana\core\strings ;

/**
 * Converts a scalar value (string, boolean, float, etc.) to a simplified, human-readable PHP string representation.
 *
 * Useful when a less verbose and more natural output is desired, especially for inline dumps or generated code.
 * Supports custom quote style for strings ('single' or 'double').
 *
 * @param mixed  $value   The scalar value to convert.
 * @param string $quote   The quote style to use for strings: 'single' or 'double'. Default is 'single'.
 * @param bool   $compact
 *
 * @return string The human-readable PHP representation of the scalar value.
 *
 * @example
 * ```php
 * echo toHumanReadableScalar("hello");                        // 'hello'
 * echo toHumanReadableScalar("He said: \"ok\"", 'double');   // "He said: \"ok\""
 * echo toHumanReadableScalar(true);                          // true
 * echo toHumanReadableScalar(42.0);                          // 42
 * echo toHumanReadableScalar(3.14);                          // 3.14
 * echo toHumanReadableScalar(null);                          // null
 * ```
 */
function toHumanReadableScalar( mixed $value , string $quote = 'single' , bool $compact = false ): string
{
    return match ( gettype( $value ) )
    {
        'string'  => formatQuotedString( $value , $quote , $compact ) ,
        'boolean' => $value ? 'true' : 'false',
        'double'  => fmod( $value , 1 ) === 0.0 ? (string)(int) $value : (string) $value ,
        default   => var_export( $value , true ) ,
    };
}