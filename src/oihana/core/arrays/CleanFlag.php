<?php

namespace oihana\core\arrays ;

use oihana\core\bits\BitFlagTrait;

/**
 * Enumeration representing cleaning modes as bit flags.
 *
 * Each flag can be combined using the bitwise OR operator (`|`) to form a mask.
 * The `has()` helper can be used to check if a particular flag is present in a mask.
 *
 * @example
 * ```php
 * use oihana\core\arrays\CleanFlag;
 *
 * // Combine multiple flags
 * $mask = CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM ;
 *
 * // Check flags
 * CleanFlag::has($mask, CleanFlag::NULLS) ; // true
 * CleanFlag::has($mask, CleanFlag::RECURSIVE) ; // false
 * ```
 */
class CleanFlag
{
    use BitFlagTrait ;

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
    public const int FALSY = 1 << 5 ;

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
     * Default cleaning: remove nulls, empty/trim strings, empty arrays recursively and return null if empty.
     */
    public const int NORMALIZE = self::DEFAULT | CleanFlag::RETURN_NULL ;

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
}