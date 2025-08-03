<?php

namespace oihana\core\strings ;

use Closure;
use stdClass;
use DateTimeInterface;
use UnitEnum;
use BackedEnum;

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