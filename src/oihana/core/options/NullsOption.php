<?php

namespace oihana\core\options;

/**
 * Defines constants for the handling of null values in merge operations.
 *
 * This class standardizes the possible behaviors for null values:
 * - SKIP       : ignore null values when merging if a key exists.
 * - KEEP       : keep null values only if the key does not exist.
 * - OVERWRITE  : overwrite existing values with null.
 *
 * @package oihana\core\options
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 *
 * @example
 * ```php
 * use oihana\core\options\NullsOption;
 * use oihana\core\options\MergeOption;
 * use function oihana\core\arrays\merge;
 *
 * $a = ['foo' => 'bar', 'count' => 2];
 * $b = ['foo' => null, 'extra' => null];
 *
 * // Use 'keep' behavior for nulls
 * $result = merge($a, $b, MergeOption::normalize
 * ([
 *     MergeOption::NULLS => NullsOption::KEEP
 * ]));
 *
 * // Result:
 * // [
 * //   'foo'   => 'bar',
 * //   'count' => 2,
 * //   'extra' => null
 * // ]
 * ```
 *
 * @package oihana\core\options
 * @author  Marc Alcaraz
 * @since   1.0.8
 */
class NullsOption
{
    /**
     * Skip null values when merging (default behavior).
     * Type: string
     */
    public const string SKIP = 'skip' ;

    /**
     * Keep null values only if the key does not exist.
     * Type: string
     */
    public const string KEEP = 'keep' ;

    /**
     * Overwrite existing values with null.
     * Type: string
     */
    public const string OVERWRITE = 'overwrite' ;

    /**
     * Returns the list of valid options.
     *
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::SKIP,
            self::KEEP,
            self::OVERWRITE,
        ];
    }

    /**
     * Checks if a given value is a valid null behavior option.
     *
     * @param string $value
     * @return bool
     */
    public static function isValid( string $value ): bool
    {
        return in_array( $value , self::all() , true ) ;
    }
}