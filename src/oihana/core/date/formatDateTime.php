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
 * This function attempts to validate and parse the input date string. If the input
 * date is not valid according to the provided format, it defaults to the current date/time ("now").
 * It then applies the specified timezone and returns the formatted date string.
 *
 * @param string|null $date     The input date/time string to format. If null or invalid, "now" is used.
 * @param string      $timezone The timezone identifier (e.g., 'Europe/Paris'). Defaults to 'UTC'.
 * @param string|null $format   The date format string compatible with DateTime::format().
 *                              Defaults to 'Y-m-d\TH:i:s.v\Z' (ISO 8601 UTC with milliseconds).
 *
 * @return string The formatted date/time string, or null if creation fails.
 *
 * @throws DateInvalidTimeZoneException If the provided timezone string is invalid.
 * @throws DateMalformedStringException If the input date string is malformed or cannot be parsed.
 *
 * @example
 * ```php
 * echo formatDateTime('2025-07-20 15:30', 'Europe/Paris', 'Y-m-d H:i');
 * // Output: '2025-07-20 15:30'
 *
 * echo formatDateTime();
 * // Output: current date/time in UTC, e.g., '2025-07-20T13:30:20.676Z'
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function formatDateTime( ?string $date = null , string $timezone = 'UTC' , ?string $format = 'Y-m-d\TH:i:s.v\Z' ): string
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

    return $dateTime->format( $format ) ;
}