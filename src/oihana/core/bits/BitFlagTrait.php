<?php

namespace oihana\core\bits;

/**
 * Trait providing common methods for bitmask flag enumerations.
 *
 * Classes using this trait MUST define the following constants:
 * - `NONE` (int)
 * - `ALL` (int)
 * - `FLAGS` (array<int>)
 * - `FLAGS_NAME` (array<int, string>)
 *
 * @package oihana\core\bits
 * @author  Marc Alcaraz
 * @since   1.0.8
 */
trait BitFlagTrait
{
    /**
     * Checks whether a specific flag is set in a bitmask.
     */
    public static function has(int $mask, int $flag): bool
    {
        return ($mask & $flag) !== 0;
    }

    /**
     * Validates that a bitmask contains only valid flags.
     */
    public static function isValid(int $mask): bool
    {
        // self::ALL will refer to the constant in the class using the trait
        return ($mask & ~self::ALL ) === 0 ;
    }

    /**
     * Gets a list of all individual flags present in a bitmask.
     *
     * @return array<int>
     */
    public static function getFlags(int $mask): array
    {
        $flags = [];

        foreach ( self::FLAGS as $flag )
        {
            if ( self::has( $mask, $flag ) )
            {
                $flags[] = $flag ;
            }
        }

        return $flags;
    }

    /**
     * Gets a human-readable description of the flags in a bitmask.
     */
    public static function describe(int $mask, string $separator = ', '): string
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

        return implode( $separator , $descriptions ) ;
    }
}