<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;
use DateTimeZone ;

/**
 * Tells whether a date falls on the current calendar day.
 *
 * A special case of `isSameDay( $date , $now )` — see there for the timezone rule : the
 * comparison is made in `$timezone`, defaulting to the timezone carried by `$date`.
 *
 * @param DateTimeInterface      $date     The date to test.
 * @param DateTimeInterface|null $now      The reference "now". Defaults to the current date/time.
 * @param DateTimeZone|null      $timezone The timezone the comparison is made in. Defaults to `$date`'s own timezone.
 *
 * @return bool `true` if `$date` falls on the same calendar day as `$now`, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\date\isToday;
 *
 * isToday( new DateTimeImmutable() ) ; // true
 * isToday( new DateTimeImmutable( '2000-01-01' ) ) ; // false
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function isToday( DateTimeInterface $date , ?DateTimeInterface $now = null , ?DateTimeZone $timezone = null ): bool
{
    return isSameDay( $date , $now ?? new DateTimeImmutable() , $timezone ) ;
}
