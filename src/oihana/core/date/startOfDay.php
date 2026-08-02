<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns the first representable instant of the day containing a date.
 *
 * A new `DateTimeImmutable` is returned, its date unchanged and its time set to
 * `00:00:00.000000` ; the source date is never modified. The timezone of the returned
 * value is the one carried by `$date`.
 *
 * @param DateTimeInterface $date The source date.
 *
 * @return DateTimeImmutable A new immutable date, at the start of the same day.
 *
 * @example
 * ```php
 * use function oihana\core\date\startOfDay;
 *
 * startOfDay( new DateTimeImmutable( '2026-03-15 14:30:45' ) ) ; // 2026-03-15 00:00:00.000000
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function startOfDay( DateTimeInterface $date ): DateTimeImmutable
{
    return DateTimeImmutable::createFromInterface( $date )->setTime( 0 , 0 , 0 , 0 ) ;
}
