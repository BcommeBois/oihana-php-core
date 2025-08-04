<?php

namespace oihana\core\strings ;

use Closure;
use DateTimeInterface;

/**
 * Converts an object to a PHP string representation with customizable formatting.
 *
 * Supports detection of circular references to prevent infinite recursion.
 * Handles special cases for DateTimeInterface, backed enums (PHP 8.1+), and Closures.
 * For generic objects, converts public properties recursively with indentation and formatting options.
 *
 * @param object $obj     The object to convert.
 * @param array  $options Formatting options including:
 *                        - 'maxDepth'    (int)    Maximum recursion depth allowed.
 *                        - 'indent'      (string) Indentation string (default 4 spaces).
 *                        - 'inline'      (bool)   Whether to output inline (no line breaks).
 *                        - 'useBrackets' (bool)   Use short array syntax `[]` instead of `array()`.
 *                        - 'quote'       (string) Quote style for strings ('single' or 'double').
 *                        - 'compact'     (bool)   Compact output for strings (no extra spaces).
 * @param int    $level   Current recursion depth level.
 * @param array  &$cache  Internal cache to track visited objects for circular reference detection.
 *
 * @return string PHP code string representing the object.
 *
 * @example
 * ```php
 * $dt = new DateTimeImmutable('2023-08-04T10:30:00+00:00');
 * echo convertObject($dt, ['maxDepth' => 3, 'indent' => '  ', 'inline' => false, 'useBrackets' => true, 'quote' => 'single', 'compact' => false], 0, []);
 *
 * // Output:
 * // new \DateTimeImmutable('2023-08-04T10:30:00+00:00')
 * ```
 * @package oihana\core\strings
 * @since 1.0.0
 * @author Marc Alcaraz
 */
function convertObject( object $obj, array $options, int $level, array &$cache ): string
{
    $id = spl_object_hash( $obj ) ;

    if ( isset( $cache[ $id ] ) )
    {
        return "'<circular-ref>'";
    }

    if ( $options['maxDepth'] <= $level )
    {
        return "'<max-depth-reached>'";
    }

    $cache[ $id ] = true ;

    try
    {
        if ( $obj instanceof DateTimeInterface )
        {
            return 'new \\' . get_class($obj) . '(' .
                formatQuotedString($obj->format('c'), $options['quote'], $options['compact']) . ')';
        }

        if ( function_exists('enum_exists') && enum_exists( get_class( $obj ) ) )
        {
            return get_class($obj) . '::' . $obj->name ;
        }

        if ($obj instanceof Closure)
        {
            return "'<closure>'";
        }

        $vars = get_object_vars( $obj ) ;

        if (empty($vars)) {
            return '/* object(' . get_class($obj) . ') */ null';
        }

        $indent      = $options[ 'indent'      ] ?? '    ' ;
        $inline      = $options[ 'inline'      ] ?? false ;
        $useBrackets = $options[ 'useBrackets' ] ?? false ;

        $pad     = str_repeat($indent, $level + 1);
        $endPad  = str_repeat($indent, $level);
        $entries = [];

        foreach ( $vars as $k => $v )
        {
            $kStr = convert($k, $options, $level + 1, $cache);
            $vStr = convert($v, $options, $level + 1, $cache);
            $entries[] = $inline ? "$kStr => $vStr" : "$pad$kStr => $vStr";
        }

        $open = $useBrackets ? '(object)[' : '(object)array(';
        $close = $useBrackets ? ']' : ')';

        if ($inline) {
            return $open . implode(', ', $entries) . $close;
        }

        return $open . PHP_EOL . implode(',' . PHP_EOL, $entries) . PHP_EOL . $endPad . $close;

    }
    finally
    {
        unset( $cache[ $id ] ) ;
    }
}