<?php

namespace oihana\core\arrays ;

/**
 * Enumeration representing cleaning modes as bit flags.
 *
 * Each flag can be combined using the bitwise OR operator (`|`) to form a mask.
 * The `has()` helper can be used to check if a particular flag is present in a mask.
 *
 * @package oihana\core\arrays
 *
 * @example
 * ```php
 * use oihana\core\arrays\CleanFlag;
 *
 * // Combine multiple flags
 * $mask = CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM;
 *
 * // Check flags
 * CleanFlag::has($mask, CleanFlag::NULLS); // true
 * CleanFlag::has($mask, CleanFlag::RECURSIVE); // false
 * ```
 */
class CleanFlag
{
    /**
     * No cleaning (edge case, returns original array)
     * @var int
     */
    public const int NONE = 0 ;

    /**
     * Remove null values
     * @var int
     */
    public const int NULLS = 1 << 0 ;

    /**
     * Remove empty strings ''
     * @var int
     */
    public const int EMPTY = 1 << 1 ;

    /**
     * Treat whitespace-only strings as empty (implies CLEAN_EMPTY)
     * @var int
     */
    public const int TRIM = 1 << 2 ;  //

    /**
     * Clean nested arrays recursively
     * @var int
     */
    public const int RECURSIVE = 1 << 3 ;

    /**
     * Remove empty arrays (after recursive cleaning)
     * @var int
     */
    public const int EMPTY_ARR = 1 << 4 ;

    /**
     * Remove the falsy values : '0', false, ...
     * @var int
     */
    public const int FALSY = 1 << 5;

    /**
     * Return null instead of empty array when result is empty
     * @var int
     */
    public const int RETURN_NULL = 1 << 6 ;

    /**
     * All valid flags combined (used for validation)
     */
    public const int ALL = self::NULLS
                         | self::EMPTY
                         | self::TRIM
                         | self::RECURSIVE
                         | self::EMPTY_ARR
                         | self::FALSY
                         | self::RETURN_NULL
                         ;

    /**
     * Default cleaning: remove nulls, empty/trim strings, empty arrays recursively
     */
    public const int DEFAULT = self::NULLS
                             | self::EMPTY
                             | self::TRIM
                             | self::RECURSIVE
                             | self::EMPTY_ARR
                             ;

    /**
     * All the main flags.
     */
    public const int MAIN = self::NULLS
                          | self::EMPTY
                          | self::EMPTY_ARR
                          | self::TRIM
                          ;

    /**
     * The default list of flags.
     */
    public const array FLAGS =
    [
        self::NULLS ,
        self::EMPTY ,
        self::TRIM ,
        self::RECURSIVE ,
        self::EMPTY_ARR ,
        self::FALSY ,
        self::RETURN_NULL ,
    ];

    /**
     * The list of flag's name.
     */
    public const array FLAGS_NAME =
    [
        self::NULLS       => 'NULLS',
        self::EMPTY       => 'EMPTY',
        self::TRIM        => 'TRIM',
        self::RECURSIVE   => 'RECURSIVE',
        self::EMPTY_ARR   => 'EMPTY_ARR',
        self::FALSY       => 'FALSY',
        self::RETURN_NULL => 'RETURN_NULL'
    ];

    /**
     * Gets a human-readable description of the flags in a bitmask.
     *
     * This method provides a string representation of which flags are active,
     * useful for debugging, logging, or user interfaces.
     *
     * @param int $mask The bitmask value to describe.
     * @param string $separator The separator between the flag descriptions.
     *
     * @return string A comma-separated (by default) string of flag names.
     *
     * @example
     * ```php
     * use oihana\core\arrays\CleanFlag;
     *
     * $mask = CleanFlag::NULLS | CleanFlag::EMPTY;
     * echo CleanFlag::describe($mask); // Outputs: "NULLS, EMPTY"
     *
     * echo CleanFlag::describe(CleanFlag::DEFAULT); // Outputs: "NULLS, EMPTY, TRIM, RECURSIVE, EMPTY_ARR"
     * ```
     */
    public static function describe( int $mask , string $separator = ', '): string
    {
        if ( $mask === self::NONE )
        {
            return 'NONE' ;
        }

        $descriptions = [] ;

        foreach ( self::FLAGS_NAME as $flag => $name )
        {
            if ( self::has( $mask , $flag ) )
            {
                $descriptions[] = $name;
            }
        }

        return implode( $separator , $descriptions );
    }

    /**
     * Gets a list of all individual flags present in a bitmask.
     *
     * This method decomposes a bitmask into its individual flag components,
     * useful for debugging or logging which flags are active.
     *
     * @param int $mask The bitmask value to decompose.
     *
     * @return array<int> An array of individual flag values present in the mask.
     *
     * @example
     * ```php
     * use oihana\core\arrays\CleanFlag;
     *
     * $mask = CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM;
     * $flags = CleanFlag::getFlags($mask);
     * // Returns [1, 2, 4] (the individual flag values)
     * ```
     */
    public static function getFlags( int $mask ): array
    {
        $flags = [] ;

        foreach ( self::FLAGS as $flag)
        {
            if ( self::has( $mask , $flag ) )
            {
                $flags[] = $flag;
            }
        }

        return $flags;
    }

    /**
     * Checks whether a specific flag is set in a bitmask.
     *
     * This method is useful when using the CleanFlag enum as a set of bit flags.
     * You can combine multiple flags using the bitwise OR operator (`|`) and then
     * check if a particular flag is present in the combined mask.
     *
     * @param int $mask The bitmask value, potentially containing multiple flags combined with `|`.
     * @param int $flag The specific flag to check for in the mask.
     *
     * @return bool Returns `true` if the given flag is present in the mask, `false` otherwise.
     *
     * @example
     * ```php
     * use oihana\core\arrays\CleanFlag;
     *
     * $mask = CleanFlag::NULLS | CleanFlag::EMPTY;
     *
     * CleanFlag::has($mask, CleanFlag::NULLS); // Returns true
     * CleanFlag::has($mask, CleanFlag::TRIM);  // Returns false
     * ```
     */
    public static function has( int $mask , int $flag ) :bool
    {
        return ( $mask & $flag ) !== 0 ;
    }

    /**
     * Validates that a bitmask contains only valid CleanFlag values.
     *
     * This method checks if the provided mask consists only of recognized flags.
     * Any bits set outside of the valid flag range will cause validation to fail.
     *
     * @param int $mask The bitmask value to validate.
     *
     * @return bool Returns `true` if the mask contains only valid flags, `false` otherwise.
     *
     * @example
     * ```php
     * use oihana\core\arrays\CleanFlag;
     *
     * CleanFlag::isValid(CleanFlag::NULLS | CleanFlag::EMPTY); // Returns true
     * CleanFlag::isValid(CleanFlag::DEFAULT);                  // Returns true
     * CleanFlag::isValid(1024);                                // Returns false (invalid flag)
     * CleanFlag::isValid(CleanFlag::NULLS | 999);              // Returns false (contains invalid bits)
     * ```
     */
    public static function isValid( int $mask ): bool
    {
        return ( $mask & ~self::ALL ) === 0 ;
    }
}