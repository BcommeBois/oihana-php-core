<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns the first representable instant of the year containing a date.
 *
 * A new `DateTimeImmutable` is returned, set to January 1st at `00:00:00.000000` ; the
 * source date is never modified. The timezone of the returned value is the one carried
 * by `$date`.
 *
 * @param DateTimeInterface $date The source date.
 *
 * @return DateTimeImmutable A new immutable date, at the start of the same year.
 *
 * @example
 * ```php
 * use function oihana\core\date\startOfYear;
 *
 * startOfYear( new DateTimeImmutable( '2026-03-15 14:30:45' ) ) ; // 2026-01-01 00:00:00.000000
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function startOfYear( DateTimeInterface $date ): DateTimeImmutable
{
    $date = DateTimeImmutable::createFromInterface( $date ) ;
    return $date->setDate( (int) $date->format( 'Y' ) , 1 , 1 )->setTime( 0 , 0 , 0 , 0 ) ;
}
