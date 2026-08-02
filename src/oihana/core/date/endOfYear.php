<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns the last representable instant of the year containing a date.
 *
 * A new `DateTimeImmutable` is returned, set to December 31st at `23:59:59.999999`
 * (microseconds included) ; the source date is never modified. The timezone of the
 * returned value is the one carried by `$date`.
 *
 * @param DateTimeInterface $date The source date.
 *
 * @return DateTimeImmutable A new immutable date, at the end of the same year.
 *
 * @example
 * ```php
 * use function oihana\core\date\endOfYear;
 *
 * endOfYear( new DateTimeImmutable( '2026-03-15 14:30:45' ) ) ; // 2026-12-31 23:59:59.999999
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function endOfYear( DateTimeInterface $date ): DateTimeImmutable
{
    $date = DateTimeImmutable::createFromInterface( $date ) ;
    return $date->setDate( (int) $date->format( 'Y' ) , 12 , 31 )->setTime( 23 , 59 , 59 , 999999 ) ;
}
