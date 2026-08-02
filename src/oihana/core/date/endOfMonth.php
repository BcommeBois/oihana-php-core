<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns the last representable instant of the month containing a date.
 *
 * A new `DateTimeImmutable` is returned, set to the last day of the month at
 * `23:59:59.999999` (microseconds included) ; the source date is never modified. The
 * timezone of the returned value is the one carried by `$date`. The last day of the
 * month is computed with `daysInGregorianMonth()`, so leap years are handled correctly.
 *
 * @param DateTimeInterface $date The source date.
 *
 * @return DateTimeImmutable A new immutable date, at the end of the same month.
 *
 * @example
 * ```php
 * use function oihana\core\date\endOfMonth;
 *
 * endOfMonth( new DateTimeImmutable( '2026-02-05' ) ) ; // 2026-02-28 23:59:59.999999
 * endOfMonth( new DateTimeImmutable( '2024-02-05' ) ) ; // 2024-02-29 23:59:59.999999 (leap year)
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function endOfMonth( DateTimeInterface $date ): DateTimeImmutable
{
    $date  = DateTimeImmutable::createFromInterface( $date ) ;
    $year  = (int) $date->format( 'Y' ) ;
    $month = (int) $date->format( 'n' ) ;
    return $date->setDate( $year , $month , daysInGregorianMonth( $year , $month ) )->setTime( 23 , 59 , 59 , 999999 ) ;
}
