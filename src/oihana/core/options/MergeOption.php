<?php

namespace oihana\core\options;

/**
 * Defines constants for the options used by the `merge` function.
 *
 * Using these constants helps avoid typos and ensures consistent option handling.
 *
 * Each constant corresponds to a configurable option:
 * - `CLEAN`         : Bitmask of CleanFlag constants for optional cleaning.
 * - `DEEP`          : Enable recursive (deep) merge.
 * - `INDEXED`       : Store values in sub-arrays (indexed).
 * - `NULLS`         : How to handle null values ('skip', 'keep', 'overwrite').
 * - `PRESERVE_KEYS` : Preserve existing keys for numeric indices.
 * - `UNIQUE`        : Remove duplicates in lists.
 *
 * @package oihana\core\options
 * @author  Marc Alcaraz
 * @since   1.0.0
 *
 * @example Using MergeOption with merge
 * ```php
 * use oihana\core\options\MergeOption;
 * use function oihana\core\arrays\merge;
 * use oihana\core\arrays\CleanFlag;
 *
 * $a = ['foo' => 'bar', 'count' => 2];
 * $b = ['foo' => null, 'new' => null];
 *
 * $result = merge($a, $b, MergeOption::normalize([
 *     MergeOption::DEEP       => true,
 *     MergeOption::UNIQUE     => true,
 *     MergeOption::NULLS      => 'skip',
 *     MergeOption::CLEAN      => CleanFlag::DEFAULT
 * ]));
 * ```
 *
 * @package oihana\core\options
 * @author  Marc Alcaraz
 * @since   1.0.8
 */
class MergeOption
{
    /**
     * Option key to defines the Bitmask of CleanFlag constants for optional cleaning.
     */
    public const string CLEAN = 'clean' ;

    /**
     * Option key to recursive merge.
     * Type: bool
     */
    public const string DEEP = 'deep' ;

    /**
     * Option key to store values in sub-array
     */
    public const string INDEXED = 'indexed' ;

    /**
     * Option key to defines the "null" behaviors ('skip'|'keep'|'overwrite')
     */
    public const string NULLS = 'nulls';

    /**
     * Option key to preserve numeric keys.
     */
    public const string PRESERVE_KEYS = 'preserveKeys' ;

    /**
     * Option key to avoid duplicates in lists.
     */
    public const string UNIQUE = 'unique' ;

    /**
     * Normalize an options array for merge function.
     *
     * Fills in defaults for missing keys and ensures consistent option names.
     *
     * @param array|null $options User-provided options
     * @return array Normalized options with default values
     *
     * @example
     * ```php
     * $opts = MergeOption::normalize
     * ([
     *     MergeOption::DEEP => true,
     *     MergeOption::NULLS => NullsOption::KEEP // "keep"
     * ]);
     *
     * // Result:
     * // [
     * //   'deep'         => true,
     * //   'indexed'      => false,
     * //   'unique'       => false,
     * //   'clean'        => null,
     * //   'preserveKeys' => true,
     * //   'nulls'        => 'keep'
     * // ]
     * ```
     */
    public static function normalize( ?array $options = [] ) :array
    {
        $options = $options ?? [];

        $nulls = $options[ self::NULLS ] ?? NullsOption::SKIP ;
        if ( !in_array( $nulls, [ NullsOption::SKIP , NullsOption::KEEP , NullsOption::OVERWRITE ] , true ) )
        {
            $nulls = NullsOption::SKIP ;
        }

        return [
            self::DEEP          => $options[ self::DEEP          ] ?? true ,
            self::INDEXED       => $options[ self::INDEXED       ] ?? false ,
            self::UNIQUE        => $options[ self::UNIQUE        ] ?? false ,
            self::CLEAN         => $options[ self::CLEAN         ] ?? null ,
            self::PRESERVE_KEYS => $options[ self::PRESERVE_KEYS ] ?? true ,
            self::NULLS         => $nulls ,
        ];
    }
}