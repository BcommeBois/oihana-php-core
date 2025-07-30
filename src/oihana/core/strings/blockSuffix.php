<?php

namespace oihana\core\strings ;

/**
 * Suffix and indent each line of a multi-line string or array of lines.
 *
 * @param array|string $lines          Array of lines or multi-line string.
 * @param string       $suffix         Suffix to append to each line.
 * @param string|int   $indent         Indentation (string or number of spaces).
 * @param string       $separator      Line separator (default: PHP_EOL).
 * @param bool         $keepEmptyLines Whether to keep empty lines (default: true).
 *
 * @return string Formatted multi-line string.
 *
 * @example
 * ```php
 * echo blockSuffix("a\n\nb", " //", 2);
 * // Output:
 * //   a //
 * //   //
 * //   b //
 *
 * echo blockSuffix(['a', '', 'b'], ' <-', '|');
 * // Output: a <-| <-|b <-
 *
 * echo blockSuffix('', '<<<');
 * // Output: <<<
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function blockSuffix
(
    array|string $lines,
    string       $suffix,
    string|int   $indent = '',
    string       $separator = PHP_EOL,
    bool         $keepEmptyLines = true
)
: string
{
    if ( is_string( $lines ) )
    {
        $lines = preg_split('/\R/', $lines, -1, $keepEmptyLines ? 0 : PREG_SPLIT_NO_EMPTY ) ;
    }
    else if ( !$keepEmptyLines )
    {
        $lines = array_filter($lines, fn( string $line ) :bool => trim( $line ) !== '' ) ;
    }

    if ( is_int( $indent ) )
    {
        $indent = str_repeat(' ', $indent);
    }

    return implode( $separator , array_map
    (
        fn(string $line): string => $indent . $line . $suffix,
        $lines
    ));
}