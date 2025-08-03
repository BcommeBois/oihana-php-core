<?php

namespace oihana\core\strings ;

use Closure;
use stdClass;

/**
 * Converts a PHP variable (array, object, or scalar) into a PHP code string representation.
 *
 * Supports nested arrays, objects, and can use either the short syntax [] or the array() syntax.
 *
 * @param mixed   $source      The data to convert (array, object, or scalar).
 * @param array $options Options to control formatting:
 * - useBrackets (bool): Use [] or array() syntax (default: false)
 * - inline      (bool): One-line output or multi-line formatted (default: true)
 * - indent      (string): Indentation characters (default: '    ')
 * - depth       (int): Current depth (internal use only)
 * - maxDepth    (int): Maximum depth (default: 20)
 *
 * @return string The PHP code string representing the variable.
 *
 * @example
 *
 * ### Example 1: Basic scalar values
 * ```php
 * echo toPhpString(42);                  // '42'
 * echo toPhpString('hello');            // '\'hello\''
 * echo toPhpString(true);               // 'true'
 * echo toPhpString(null);               // 'null'
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
 * $data = [
 * 'user' => [
 * 'name' => 'Alice',
 * 'roles' => ['admin', 'editor'],
 * 'profile' => (object)['active' => true, 'age' => 30]
 * ]
 * ];
 * echo toPhpString($data, ['useBrackets' => true]);
 * // Output: ['user' => ['name' => 'Alice', 'roles' => ['admin', 'editor'], 'profile' => (object)['active' => true, 'age' => 30]]]
 * ```
 *
 * ### Example 4: Multiline formatted with indentation
 * ```php
 * echo toPhpString($data, [
 * 'useBrackets' => true,
 * 'inline' => false,
 * 'indent' => '  '
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
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function toPhpString( mixed $source , array $options = [] ): string
{
    $options =
    [
        'useBrackets' => false,
        'inline'      => true ,
        'indent'      => '    ',
        'depth'       => 0,
        'maxDepth'    => 20,
        ...$options
    ];

    $useBrackets = $options[ 'useBrackets' ] ;
    $inline      = $options[ 'inline'      ] ;
    $indent      = $options[ 'indent'      ] ;
    $depth       = $options[ 'depth'       ] ;
    $maxDepth    = $options[ 'maxDepth'    ] ;

    $processValue = function( mixed $value ) use ( &$processValue, $options ): string
    {
        if ( $options['depth'] >= $options['maxDepth'] )
        {
            return "'<max-depth-reached>'";
        }

        if ( is_resource( $value ) )
        {
            return "'<resource of type " . get_resource_type( $value ) . ">'";
        }

        if ($value instanceof Closure)
        {
            return "'<closure>'";
        }

        if ( is_array( $value ) )
        {
            return toPhpString( $value, [ ...$options , 'depth' => $options['depth'] + 1 ] ) ;
        }

        if ( is_object( $value ) )
        {
            if ( $value instanceof stdClass )
            {
                return '(object)' . toPhpString( (array) $value, [ ...$options , 'depth' => $options['depth'] + 1 ] ) ;
            }

            return '(object)' . toPhpString(get_object_vars($value), [ ...$options , 'depth' => $options['depth'] + 1 ] );
        }

        if ( is_null( $value ) )
        {
            return 'null' ;
        }

        return var_export( $value , true ) ;
    };

    if ( !is_array( $source ) )
    {
        return $processValue( $source ) ;
    }

    $isSequential = array_keys($source) === range(0 , count( $source ) - 1 ) ;
    $elements     = [] ;

    foreach ( $source as $key => $value )
    {
        $valueStr = $processValue( $value ) ;

        if ( $isSequential )
        {
            $elements[] = $valueStr;
        }
        else
        {
            $keyStr      = is_int( $key ) ? $key : "'" . addslashes($key) . "'" ;
            $elements[] = $keyStr . ' => ' . $valueStr ;
        }
    }

    $open  = $useBrackets ? '[' : 'array(' ;
    $close = $useBrackets ? ']' : ')'      ;

    if ( $inline || empty( $elements ) )
    {
        return $open . implode(', ' , $elements ) . $close ;
    }

    $pad        = str_repeat( $indent , $depth + 1 ) ;
    $lines      = array_map( fn( $e ) => $pad . $e, $elements ) ;
    $closingPad = str_repeat( $indent , $depth ) ;

    return $open . PHP_EOL . implode("," . PHP_EOL , $lines) . PHP_EOL . $closingPad . $close;
}