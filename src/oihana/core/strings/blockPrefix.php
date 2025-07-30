<?php

namespace oihana\core\strings ;

/**
 * Prefix and indent each line of a multi-line string or array of lines.
 *
 * @param array|string $lines          Array of lines or multi-line string.
 * @param string       $prefix         Prefix to apply before each line.
 * @param string|int   $indent         Indentation (string or number of spaces).
 * @param string       $separator      Line separator (default: PHP_EOL).
 * @param bool         $keepEmptyLines Whether to keep empty lines (default: true).
 *
 * @return string Formatted multi-line string.
 *
 * @example
 * ```php
 * echo blockPrefix("a\n\nb", "// ", 2);
 * // Output:
 * //   // a
 * //   //
 * //   // b
 *
 * echo blockPrefix(['a', '', 'b'], '-> ', '|');
 * // Output: -> a|-> |-> b
 *
 * echo blockPrefix('', '>>>');
 * // Output: >>>
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function blockPrefix( array|string $lines , string $prefix , string|int $indent = '' , string $separator = PHP_EOL , bool $keepEmptyLines = true ): string
{
    if ( is_string( $lines ) )
    {
        $lines = preg_split('/\R/', $lines, -1, $keepEmptyLines ? 0 : PREG_SPLIT_NO_EMPTY) ;
    }
    elseif ( !$keepEmptyLines )
    {
        $lines = array_filter($lines, fn(string $line): bool => trim($line) !== '');
    }

    if ( is_int( $indent ) )
    {
        $indent = str_repeat(' ' , $indent ) ;
    }

    return implode( $separator , array_map
    (
        fn(string $line): string => $prefix === '' && $indent === '' && $line === '' && !$keepEmptyLines
            ? ''
            : $indent . $prefix . $line,
        $lines
    ));
}