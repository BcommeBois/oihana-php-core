<?php

namespace oihana\core\date ;

use DateMalformedStringException;
use DateInvalidTimeZoneException;

/**
 * Returns the current date/time as a formatted string.
 *
 * @param string $timezone The timezone identifier (e.g., 'Europe/Paris'). Defaults to 'UTC'.
 * @param string|null $format  The date format string compatible with DateTime::format(). Defaults to 'Y-m-d\TH:i:s.v\Z' (ISO 8601 UTC with milliseconds).
 *
 * @return string The formatted current date/time string.
 *
 * @throws DateInvalidTimeZoneException If the provided timezone string is invalid.
 * @throws DateMalformedStringException If the date creation fails (should not occur with 'now').
 *
 * @example
 * ```php
 * echo now() ;
 * // Output: '2025-07-20T15:30.676Z'
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function now( string $timezone = 'UTC' , ?string $format = 'Y-m-d\TH:i:s.v\Z' ): string
{
    return formatDateTime( 'now' , $timezone , $format );
}