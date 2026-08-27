<?php

namespace oihana\core\date ;

use Exception;
use DateMalformedStringException;
use DateInvalidTimeZoneException;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Formats a given date/time string into a specified format and timezone.
 *
 * The `$timezone` parameter is used only to **interpret** the input date string, and
 * only when that string does not carry an offset of its own — `2025-07-20T09:30:00+02:00`
 * says which moment it is, so PHP's own parser ignores `$timezone` for it.
 *
 * The moment is then **rendered** in the timezone it was parsed in, except when
 * `$format` asks for a literal `Z` (an escaped `\Z`, as in the default format) : `Z` is
 * the ISO-8601 designator for UTC, so the moment is converted to UTC first and the
 * suffix tells the truth. A format carrying a real offset token (`P`, `O`, `T`) is left
 * in its own timezone, that token already saying which one it is.
 *
 * @param string|null $date     The input date/time string to format. If null, "now" is used.
 * @param string      $timezone The timezone identifier (e.g., 'Europe/Paris'). Defaults to 'UTC'.
 * @param string|null $format   The date format string compatible with DateTime::format(). If null,
 *                              {@see DateFormat::DEFAULT} is used (ISO 8601 UTC with milliseconds).
 *
 * @return string The formatted date/time string.
 *
 * @throws DateInvalidTimeZoneException If the provided timezone string is invalid.
 * @throws DateMalformedStringException If the input date string is malformed or cannot be parsed.
 *
 * @example
 * ```php
 * echo formatDateTime( '2025-07-20 15:30' , 'Europe/Paris' , 'Y-m-d H:i' ) ;
 * // Output: '2025-07-20 15:30' — no literal Z, rendered in Paris as parsed.
 *
 * echo formatDateTime( '2025-07-20T09:30:00+02:00' ) ;
 * // Output: '2025-07-20T07:30:00.000Z' — the default format ends on a literal Z, so UTC.
 *
 * echo formatDateTime() ;
 * // Output: current date/time in UTC, e.g., '2025-07-20T13:30:20.676Z'
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function formatDateTime( ?string $date = null , string $timezone = 'UTC' , ?string $format = DateFormat::DEFAULT ): string
{
    if ( !isValidTimezone( $timezone ) )
    {
        throw new DateInvalidTimeZoneException("Invalid timezone: '{$timezone}'");
    }

    try
    {
        $dateTime = new DateTimeImmutable($date ?? 'now' , new DateTimeZone( $timezone ) ) ;
    }
    catch ( Exception $exception )
    {
        $input = $date ?? 'now' ;
        throw new DateMalformedStringException
        (
            "Malformed date string: '{$input}'" ,
            0 ,
            $exception
        ) ;
    }

    $format ??= DateFormat::DEFAULT ;

    if ( hasLiteralZulu( $format ) )
    {
        $dateTime = $dateTime->setTimezone( new DateTimeZone( 'UTC' ) ) ;
    }

    return $dateTime->format( $format ) ;
}
