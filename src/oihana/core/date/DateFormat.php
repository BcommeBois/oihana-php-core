<?php

namespace oihana\core\date ;

/**
 * Defines the canonical date/time format patterns of the library.
 *
 * Using these constants instead of the raw pattern strings keeps the producers in
 * sync — for instance the default format shared by {@see formatDateTime()} and
 * {@see now()}.
 *
 * @example
 * ```php
 * use oihana\core\date\DateFormat;
 * use function oihana\core\date\formatDateTime;
 *
 * echo formatDateTime( '2025-07-20T09:30:00+02:00' , format : DateFormat::ZULU_MILLI ) ; // 2025-07-20T07:30:00.000Z
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
class DateFormat
{
    /**
     * ISO-8601 in UTC, with milliseconds and a literal `Z` suffix — e.g. `2025-07-20T07:30:00.000Z`.
     *
     * The `Z` is escaped, hence a literal : {@see formatDateTime()} converts the moment
     * to UTC before formatting, so that the suffix tells the truth.
     *
     * Type: string
     */
    public const string ZULU_MILLI = 'Y-m-d\TH:i:s.v\Z' ;

    /**
     * The format used by {@see formatDateTime()} and {@see now()} when none is given.
     *
     * Type: string
     */
    public const string DEFAULT = self::ZULU_MILLI ;
}
