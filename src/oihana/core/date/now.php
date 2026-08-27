<?php

namespace oihana\core\date ;

use DateMalformedStringException;
use DateInvalidTimeZoneException;

/**
 * Returns the current date/time as a formatted string.
 *
 * The `$timezone` parameter is used only to interpret the "now" value. Since the default
 * format ends on a literal `Z`, the ISO-8601 designator for UTC, the result is converted to
 * UTC whichever timezone is asked for — see {@see formatDateTime()} for the full rule.
 *
 * @param string      $timezone The timezone identifier (e.g., 'Europe/Paris'). Defaults to 'UTC'.
 * @param string|null $format   The date format string compatible with DateTime::format(). Defaults to
 *                              {@see DateFormat::DEFAULT} (ISO 8601 UTC with milliseconds).
 *
 * @return string The formatted current date/time string.
 *
 * @throws DateInvalidTimeZoneException If the provided timezone string is invalid.
 * @throws DateMalformedStringException If the date creation fails (should not occur with 'now').
 *
 * @example
 * ```php
 * echo now() ;
 * // Output: '2025-07-20T13:30:20.676Z'
 *
 * echo now( 'Europe/Paris' ) ;
 * // Output: '2025-07-20T13:30:20.676Z' (the same moment — the default format is UTC)
 *
 * echo now( 'Europe/Paris' , 'H:i' ) ;
 * // Output: '15:30' (no literal Z, so the Paris wall clock)
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function now( string $timezone = 'UTC' , ?string $format = DateFormat::DEFAULT ): string
{
    return formatDateTime( 'now' , $timezone , $format );
}