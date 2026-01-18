<?php

namespace oihana\core\arrays ;

/**
 * Reduces an array by removing values based on conditions or using compress/clean.
 *
 * This function provides a unified interface for array reduction with three modes:
 * - `true`: Remove null values using compress()
 * - `array`: Forward options to compress()
 * - `callable`: Custom filter function
 *
 * @param array                $array   The input array.
 * @param bool|array|callable  $reduce  Reduction mode:
 *                                      - `true`: compress with default options
 *                                      - `array`: compress with custom options
 *                                      - `callable`: fn($value, $key): bool
 *
 * @return array The reduced array.
 *
 * @example Remove nulls (default compress)
 * ```php
 * $data = ['a' => 1, 'b' => null, 'c' => 2];
 * $result = reduce($data, true);
 * // ['a' => 1, 'c' => 2]
 * ```
 *
 * @example Custom compress options
 * ```php
 * use oihana\core\options\CompressOption;
 *
 * $data = ['a' => '', 'b' => null, 'c' => 'ok'];
 * $result = reduce( $data ,
 * [
 *     CompressOption::CONDITIONS =>
 *     [
 *         fn($v) => $v === null,
 *         fn($v) => $v === ''
 *     ]
 * ]);
 * // ['c' => 'ok']
 * ```
 *
 * @example Custom callable
 * ```php
 * $data = ['name' => 'Alice', 'age' => 0, 'city' => 'Paris'];
 * $result = reduce($data, fn($v, $k) => is_string($v) && $v !== '');
 * // ['name' => 'Alice', 'city' => 'Paris']
 * ```
 *
 * @example Empty array
 * ```php
 * $result = reduce([], true);
 * // []
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz
 * @since   1.0.8
 */
function reduce( array $array , bool|array|callable $reduce = true ): array
{
    return match ( true )
    {
        $reduce === true     => compress( $array ) ,
        is_array( $reduce )  => compress( $array , $reduce ) ,
        is_callable($reduce) => array_filter($array, fn($v, $k) => $reduce($v, $k), ARRAY_FILTER_USE_BOTH),
        default              => $array
    };
}