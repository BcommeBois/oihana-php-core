<?php

namespace oihana\core\strings ;

/**
 * Converts an associative or sequential PHP array into a human-readable PHP string representation.
 *
 * This function is typically used as part of a recursive serialization process to represent array structures
 * in a PHP-style syntax. It supports pretty-printing, inline formatting, bracketed or classic `array()` syntax,
 * and limits recursion via the `maxDepth` option.
 *
 * The output is intended for debugging, code generation, or inspection tools.
 *
 * @param array $array   The array to convert.
 * @param array $options An associative array of formatting options:
 *                       - 'indent'       (string): The indentation string (e.g., `'    '`).
 *                       - 'inline'       (bool): If true, output the array on a single line.
 *                       - 'useBrackets'  (bool): If true, use `[]` instead of `array()`.
 *                       - 'maxDepth'     (int): The maximum recursion depth.
 * @param int   $level   The current depth level in the recursion (used for indentation).
 * @param array &$cache  A reference to a cache array used for detecting circular references.
 *
 * @return string The PHP-like string representation of the array.
 *
 * @example
 * ```php
 * $array = [
 *     'name' => 'Alice',
 *     'age'  => 30,
 *     'tags' => ['developer', 'php'],
 * ];
 *
 * echo convertArray( $array ,
 * [
 *     'indent'      => '  ',
 *     'inline'      => false,
 *     'useBrackets' => true,
 *     'maxDepth'    => 3,
 * ], 0, $cache = []);
 *
 * // Output:
 * // [
 * //   'name' => 'Alice',
 * //   'age' => 30,
 * //   'tags' => [
 * //     'developer',
 * //     'php'
 * //   ]
 * // ]
 * ```
 *
 * @see convert() For the recursive value conversion used within this function.
 *
 * @package oihana\core\strings
 * @since 1.0.0
 * @since 1.0.0
 */
function convertArray(array $array, array $options , int $level , array &$cache ): string
{
    $indent      = $options[ 'indent'      ] ?? '    ' ;
    $inline      = $options[ 'inline'      ] ?? false  ;
    $maxDepth    = $options[ 'maxDepth'    ] ?? 10     ;
    $useBrackets = $options[ 'useBrackets' ] ?? false  ;

    if ( $maxDepth <= $level )
    {
        return "'<max-depth-reached>'";
    }

    $pad     = str_repeat( $indent , $level + 1 ) ;
    $endPad  = str_repeat( $indent , $level ) ;
    $entries = [] ;

    $isSequential = array_is_list( $array ) ; // PHP 8.1+ ou fonction custom

    foreach ( $array as $k => $v )
    {
        $vStr = convert( $v , $options , $level + 1 , $cache ) ;

        if ( $isSequential )
        {
            $entries[] = $inline ? $vStr : "$pad$vStr";
        }
        else
        {
            $kStr      = convert( $k , $options , $level + 1 , $cache ) ;
            $entries[] = $inline ? "$kStr => $vStr" : "$pad$kStr => $vStr";
        }
    }

    $open  = $useBrackets ? '[' : 'array(' ;
    $close = $useBrackets ? ']' : ')'      ;

    if ( $inline )
    {
        return $open . implode(', ', $entries) . $close;
    }

    if ( empty( $entries ) )
    {
        return $open . $close;
    }

    return $open . PHP_EOL . implode(',' . PHP_EOL, $entries) . PHP_EOL . $endPad . $close;
}