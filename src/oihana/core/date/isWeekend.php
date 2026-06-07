<?php

namespace oihana\core\date ;

use DateTimeInterface ;

/**
 * Tells whether a date falls on a weekend (Saturday or Sunday).
 *
 * @param DateTimeInterface $date The date to test.
 *
 * @return bool `true` if the date is a Saturday or a Sunday, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\date\isWeekend;
 *
 * isWeekend( new DateTimeImmutable( '2024-01-06' ) ) ; // true  (Saturday)
 * isWeekend( new DateTimeImmutable( '2024-01-08' ) ) ; // false (Monday)
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function isWeekend( DateTimeInterface $date ): bool
{
    return (int) $date->format( 'N' ) >= 6 ;
}
