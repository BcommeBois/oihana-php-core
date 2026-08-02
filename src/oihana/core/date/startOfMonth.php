<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns the first representable instant of the month containing a date.
 *
 * A new `DateTimeImmutable` is returned, set to the 1st of the month at
 * `00:00:00.000000` ; the source date is never modified. The timezone of the returned
 * value is the one carried by `$date`.
 *
 * @param DateTimeInterface $date The source date.
 *
 * @return DateTimeImmutable A new immutable date, at the start of the same month.
 *
 * @example
 * ```php
 * use function oihana\core\date\startOfMonth;
 *
 * startOfMonth( new DateTimeImmutable( '2026-03-15 14:30:45' ) ) ; // 2026-03-01 00:00:00.000000
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function startOfMonth( DateTimeInterface $date ): DateTimeImmutable
{
    $date = DateTimeImmutable::createFromInterface( $date ) ;
    return $date->setDate( (int) $date->format( 'Y' ) , (int) $date->format( 'n' ) , 1 )->setTime( 0 , 0 , 0 , 0 ) ;
}
