<?php

namespace oihana\core\strings;

use Closure;
use DateTimeInterface;

/**
 * Converts any PHP value into a valid PHP code string representation.
 *
 * @param mixed $value The value to convert.
 * @param array{
 *    compact?: bool,
 *    humanReadable?: bool,
 *    inline?: bool,
 *    indent?: string|int,
 *    maxDepth?: int,
 *    quote?: 'single'|'double',
 *    useBrackets?: bool
 * } $options Formatting options:
 * - 'compact'         => (bool) Escape control chars even in single-quote (default: false)
 * - 'humanReadable'   => (bool) Human-friendly formatting for scalars (default: false)
 * - 'inline'          => (bool) Single-line output (default: false)
 * - 'indent'          => (string) Indentation string per level (default: '    ')
 * - 'maxDepth'        => (int) Max recursion depth (default: 10)
 * - 'quote'           => ('single'|'double') Quote style for strings (default: 'single')
 * - 'useBrackets'     => (bool) Use brackets for arrays (default: false)
 *
 * @return string The PHP code string representing the variable.
 *
 * @example
 *
 * ### Example 1: Basic scalar values
 * ```php
 * echo toPhpString(42);       // '42'
 * echo toPhpString('hello'); // '\'hello\''
 * echo toPhpString(true);    // 'true'
 * echo toPhpString(null);    // 'null'
 * ```
 *
 * ### Example 2: Flat array with short syntax
 * ```php
 * echo toPhpString(['a' => 1, 'b' => 2], ['useBrackets' => true]);
 * // Output: ['a' => 1, 'b' => 2]
 * ```
 *
 * ### Example 3: Nested object and array, inline
 * ```php
 * $data =
 * [
 *     'user' =>
 *     [
 *         'name' => 'Alice',
 *         'roles' => ['admin', 'editor'],
 *         'profile' => (object)['active' => true, 'age' => 30]
 *     ]
 * ];
 * echo toPhpString($data, ['useBrackets' => true]);
 * // Output: ['user' => ['name' => 'Alice', 'roles' => ['admin', 'editor'], 'profile' => (object)['active' => true, 'age' => 30]]]
 * ```
 *
 * ### Example 4: Multiline formatted with indentation
 * ```php
 * echo toPhpString( $data ,
 * [
 *     'useBrackets' => true,
 *     'inline' => false,
 *     'indent' => 2
 * ]);
 * // Output:
 * // [
 * //   'user' => [
 * //     'name' => 'Alice',
 * //     'roles' => [
 * //       'admin',
 * //       'editor'
 * //     ],
 * //     'profile' => (object)[
 * //       'active' => true,
 * //       'age' => 30
 * //     ]
 * //   ]
 * // ]
 * ```
 *
 * ### Example 5: Object with closure and max depth
 * ```php
 * $obj = new stdClass();
 * $obj->callback = fn() => null;
 * $obj->deep = ['level1' => ['level2' => ['level3' => ['level4' => $obj]]]];
 *
 * echo toPhpString($obj, ['maxDepth' => 3, 'useBrackets' => true]);
 * // Output includes: '<max-depth-reached>', '<closure>'
 *
 * ### Example 6: Use double quotes and a human readable rendering
 * ```php
 * $data =
 * [
 *    'message' => "Hello\nWorld",
 *    'count'   => 1.0,
 *    'active'  => true
 * ];
 *
 * echo toPhpString( $data,
 * [
 *    'useBrackets'   => true,
 *    'humanReadable' => true,
 *    'quote'         => 'double'
 * ]);
 *
 * // Output
 * // [
 * //     'message' => "Hello\nWorld",
 * //     'count'   => 1,
 * //     'active'  => true
 * // ]
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function toPhpString( mixed $value , array $options = [] ): string
{
    $defaults =
    [
        'compact'        => false    ,
        'inline'         => true     ,
        'indent'         => '    '   ,
        'maxDepth'       => 10       ,
        'humanReadable'  => false    ,
        'quote'          => 'single' ,
        'useBrackets'    => false    ,
    ];

    $options = array_merge( $defaults , $options ) ;
    $cache   = [] ;

    return convert( $value , $options , 0 , $cache ) ;
}

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
        return toPhpHumanReadableScalar( $value, $quote, $compact ) ;
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




/**
 * Converts an object to a PHP string representation with customizable formatting.
 *
 * Supports detection of circular references to prevent infinite recursion.
 * Handles special cases for DateTimeInterface, backed enums (PHP 8.1+), and Closures.
 * For generic objects, converts public properties recursively with indentation and formatting options.
 *
 * @param object $obj The object to convert.
 * @param array $options Formatting options including:
 *                        - 'maxDepth'    (int)    Maximum recursion depth allowed.
 *                        - 'indent'      (string) Indentation string (default 4 spaces).
 *                        - 'inline'      (bool)   Whether to output inline (no line breaks).
 *                        - 'useBrackets' (bool)   Use short array syntax `[]` instead of `array()`.
 *                        - 'quote'       (string) Quote style for strings ('single' or 'double').
 *                        - 'compact'     (bool)   Compact output for strings (no extra spaces).
 * @param int $level Current recursion depth level.
 * @param array  &$cache Internal cache to track visited objects for circular reference detection.
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
function convertObject( object $obj , array $options , int $level , array &$cache ) : string
{
    $id = spl_object_hash( $obj );

    if ( isset( $cache[ $id ] ) ) {
        return "'<circular-ref>'";
    }

    if ( $options['maxDepth'] <= $level ) {
        return "'<max-depth-reached>'";
    }

    $cache[ $id ] = true;

    try {
        if ( $obj instanceof DateTimeInterface ) {
            return 'new \\' . get_class( $obj ) . '(' .
                formatQuotedString( $obj->format( 'c' ) , $options['quote'] , $options['compact'] ) . ')';
        }

        if ( function_exists( 'enum_exists' ) && enum_exists( get_class( $obj ) ) ) {
            return get_class( $obj ) . '::' . $obj->name;
        }

        if ( $obj instanceof Closure ) {
            return "'<closure>'";
        }

        $vars = get_object_vars( $obj );

        if ( empty( $vars ) ) {
            return '/* object(' . get_class( $obj ) . ') */ null';
        }

        $indent = $options['indent'] ?? '    ';
        $inline = $options['inline'] ?? false;
        $useBrackets = $options['useBrackets'] ?? false;

        $pad = str_repeat( $indent , $level + 1 );
        $endPad = str_repeat( $indent , $level );
        $entries = [];

        foreach ( $vars as $k => $v ) {
            $kStr = convert( $k , $options , $level + 1 , $cache );
            $vStr = convert( $v , $options , $level + 1 , $cache );
            $entries[] = $inline ? "$kStr => $vStr" : "$pad$kStr => $vStr";
        }

        $open = $useBrackets ? '(object)[' : '(object)array(';
        $close = $useBrackets ? ']' : ')';

        if ( $inline ) {
            return $open . implode( ', ' , $entries ) . $close;
        }

        return $open . PHP_EOL . implode( ',' . PHP_EOL , $entries ) . PHP_EOL . $endPad . $close;

    }
    finally
    {
        unset( $cache[ $id ] );
    }
}

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