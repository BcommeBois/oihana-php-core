<?php

namespace oihana\core\options;

use function oihana\core\helpers\conditions;

/**
 * Defines constants for the options used by the `compress` functions
 * for arrays and objects.
 *
 * Using these constants helps avoid typos when passing options
 * to `oihana\core\arrays\compress` or `oihana\core\objects\compress`.
 *
 * Each constant corresponds to a specific configurable option:
 * - `CLONE`       : Whether to clone the input before compression.
 * - `CONDITIONS`  : One or more callable conditions to remove values.
 * - `EXCLUDES`    : Keys to skip during compression.
 * - `DEPTH`       : Maximum recursion depth for nested arrays/objects.
 * - `RECURSIVE`   : Whether to compress nested arrays/objects recursively.
 * - `REMOVE_KEYS` : Keys to forcibly remove regardless of conditions.
 * - `THROWABLE`   : Whether invalid conditions throw exceptions.
 *
 * @package oihana\core\options
 * @author  Marc Alcaraz
 * @since   1.0.0
 *
 * @example Using constants with compress
 * ```php
 * use oihana\core\options\CompressOption;
 * use function oihana\core\arrays\compress;
 *
 * $data =
 * [
 *   'id'    => 1,
 *   'name'  => null,
 *   'debug' => 'keep me'
 * ];
 *
 * $clean = compress( $data ,
 * [
 *    CompressOption::EXCLUDES    => ['debug'] , // don't remove 'debug'
 *    CompressOption::REMOVE_KEYS => ['id'] ,    // forcibly remove 'id'
 *    CompressOption::RECURSIVE   => true,
 *    CompressOption::THROWABLE   => true
 * ]);
 * ```
 */
class CompressOption
{
    /**
     * Option key to clone the array/object before compressing.
     * If true, the original is not modified.
     *
     * Type: bool
     */
    public const string CLONE = 'clone' ;

    /**
     * One or more callbacks: fn(mixed $value): bool.
     * If any returns true, the entry is removed.
     *
     * Type: callable|callable[]
     */
    public const string CONDITIONS = 'conditions' ;

    /**
     * Option key for property/array keys to exclude from compression.
     * Keys listed here will not be removed even if conditions match.
     *
     * Type: string[]
     */
    public const string EXCLUDES = 'excludes'   ;

    /**
     * Option key to limit recursion depth when compressing nested arrays/objects.
     * Null means no limit.
     *
     * Type: int|null
     */
    public const string DEPTH = 'depth' ;

    /**
     * Option key to enable recursive compression of nested arrays/objects.
     *
     * Type: bool
     */
    public const string RECURSIVE = 'recursive' ;

    /**
     * Option key for property/array keys to forcibly remove.
     * Keys listed here are removed regardless of conditions.
     *
     * Type: string[]
     */
    public const string REMOVE_KEYS = 'removeKeys' ;

    /**
     * Option key to control whether invalid conditions throw exceptions.
     *
     * Type: bool
     */
    public const string THROWABLE = 'throwable' ;

    /**
     * Normalize an options array for compress functions.
     *
     * Fills in defaults for missing keys and ensures consistent option names.
     *
     * @param array|null $options User-provided options
     * @return array Normalized options with default values
     *
     * @example
     * ```php
     * $opts = CompressOption::normalize([
     *     CompressOption::EXCLUDES => ['id'],
     *     CompressOption::RECURSIVE => true
     * ]);
     *
     * // Result:
     * // [
     * //   'clone' => false,
     * //   'excludes' => ['id'],
     * //   'depth' => null,
     * //   'recursive' => true,
     * //   'removeKeys' => null,
     * //   'throwable' => true
     * // ]
     * ```
     */
    public static function normalize( ?array $options = [] ) :array
    {
        $options   = $options ?? [];
        $throwable = $options[ self::THROWABLE ] ?? true ;
        return
        [
            self::CLONE       => $options[ self::CLONE       ] ?? false ,
            self::EXCLUDES    => $options[ self::EXCLUDES    ] ?? null  ,
            self::DEPTH       => $options[ self::DEPTH       ] ?? null  ,
            self::RECURSIVE   => $options[ self::RECURSIVE   ] ?? false ,
            self::REMOVE_KEYS => $options[ self::REMOVE_KEYS ] ?? null  ,
            self::THROWABLE   => $throwable,
            self::CONDITIONS  => conditions($options[self::CONDITIONS] ?? null, $throwable),
        ];
    }
}