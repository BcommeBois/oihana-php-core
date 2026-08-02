<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns the last representable instant of the day containing a date.
 *
 * A new `DateTimeImmutable` is returned, its date unchanged and its time set to
 * `23:59:59.999999` (microseconds included) ; the source date is never modified. The
 * timezone of the returned value is the one carried by `$date`.
 *
 * @param DateTimeInterface $date The source date.
 *
 * @return DateTimeImmutable A new immutable date, at the end of the same day.
 *
 * @example
 * ```php
 * use function oihana\core\date\endOfDay;
 *
 * endOfDay( new DateTimeImmutable( '2026-03-15 14:30:45' ) ) ; // 2026-03-15 23:59:59.999999
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function endOfDay( DateTimeInterface $date ): DateTimeImmutable
{
    return DateTimeImmutable::createFromInterface( $date )->setTime( 23 , 59 , 59 , 999999 ) ;
}
