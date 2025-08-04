<?php

namespace oihana\core\strings ;

/**
 * Recursively converts any PHP value into a PHP code string representation.
 *
 * Supports scalars, arrays, objects, and resources with options for formatting,
 * indentation, recursion depth control, and human-readable scalar output.
 * Handles circular references to prevent infinite loops.
 *
 * @param mixed $value   The value to convert (scalar, array, object, resource, etc.).
 * @param array $options Formatting options, including:
 *                       - 'maxDepth'    (int)    Maximum recursion depth allowed.
 *                       - 'indent'      (string) Indentation string (default 4 spaces).
 *                       - 'inline'      (bool)   Whether to output inline (no line breaks).
 *                       - 'useBrackets' (bool)   Use short array syntax `[]` instead of `array()`.
 *                       - 'quote'       (string) Quote style for strings ('single' or 'double').
 *                       - 'compact'     (bool)   Compact output for strings (no extra spaces).
 *                       - 'humanReadable' (bool) Whether to use simplified scalar formatting.
 * @param int   $level   Current recursion depth level (used internally).
 * @param array &$cache  Internal cache to track visited objects/arrays for circular reference detection.
 *
 * @return string PHP code string representing the given value.
 *
 * @example
 * ```php
 * $data = [
 *     'name' => "Oihana",
 *     'count' => 42,
 *     'active' => true,
 *     'nested' => ['a', 'b', 'c']
 * ];
 *
 * echo convert($data, ['maxDepth' => 3, 'indent' => '  ', 'inline' => false, 'useBrackets' => true, 'quote' => 'single', 'compact' => false, 'humanReadable' => true], 0, []);
 *
 * // Output:
 * // [
 * //   'name' => 'Oihana',
 * //   'count' => 42,
 * //   'active' => true,
 * //   'nested' => [
 * //     'a',
 * //     'b',
 * //     'c',
 * //   ],
 * // ]
 * ```
 */
function convert( mixed $value , array $options , int $level , array &$cache ) :string
{
    if ( is_resource( $value ) )
    {
        $type = get_resource_type( $value ) ;
        if ( $type === 'Unknown' )
        {
            return "'<closed resource>'" ;
        }
        return "'<resource of type " . $type . ">'";
    }

    $type = gettype( $value ) ;

    $compact       = $options[ 'compact'       ] ?? false    ;
    $humanReadable = $options[ 'humanReadable' ] ?? false    ;
    $quote         = $options[ 'quote'         ] ?? 'single' ;

    if ( $humanReadable && in_array( $type, [ 'string' , 'boolean' , 'double' , 'integer' ] ) )
    {
        return toHumanReadableScalar( $value, $quote, $compact ) ;
    }

    return match ( $type )
    {
        'string'             => formatQuotedString( $value , $quote , $compact ) ,
        'boolean'            => $value ? 'true' : 'false',
        'integer' , 'double' => var_export( $value , true ) ,
        'array'              => convertArray  ( $value , $options , $level , $cache ) ,
        'object'             => convertObject ( $value , $options , $level , $cache ) ,
        default              => 'null',
    };
}