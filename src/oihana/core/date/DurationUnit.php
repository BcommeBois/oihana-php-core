<?php

namespace oihana\core\date ;

/**
 * Defines the canonical single-letter suffixes of a duration unit.
 *
 * Using these constants instead of the raw `'d'` / `'h'` / `'m'` / `'s'` magic
 * strings keeps the parser and the formatter in sync — for instance the unit
 * strings consumed by {@see durationToSeconds()} and produced by
 * {@see humanizeDuration()}.
 *
 * @example
 * ```php
 * use oihana\core\date\DurationUnit;
 * use function oihana\core\date\durationToSeconds;
 *
 * echo durationToSeconds( '1' . DurationUnit::HOUR . ' 30' . DurationUnit::MINUTE ) ; // 5400
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
class DurationUnit
{
    /**
     * The day suffix.
     * Type: string
     */
    public const string DAY = 'd' ;

    /**
     * The hour suffix.
     * Type: string
     */
    public const string HOUR = 'h' ;

    /**
     * The minute suffix.
     * Type: string
     */
    public const string MINUTE = 'm' ;

    /**
     * The second suffix.
     * Type: string
     */
    public const string SECOND = 's' ;

    /**
     * Returns the list of valid duration unit suffixes, from the largest to the smallest.
     *
     * @return string[]
     */
    public static function all() : array
    {
        return [
            self::DAY ,
            self::HOUR ,
            self::MINUTE ,
            self::SECOND ,
        ] ;
    }

    /**
     * Checks if a given value is a valid duration unit suffix.
     *
     * @param string $value
     * @return bool
     */
    public static function isValid( string $value ) : bool
    {
        return in_array( $value , self::all() , true ) ;
    }
}
