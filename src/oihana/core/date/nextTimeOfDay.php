<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;
use InvalidArgumentException ;

/**
 * Finds the next moment a given time of day comes round, strictly after another.
 *
 * A limit written as an hour — « stop starting work at 05:30 » — means the next 05:30, whatever
 * the moment it is read: the same day when that hour is still ahead, the next day when it has
 * passed **or is now**. A job started at 23:00 and one started at 02:00 the same night both stop
 * at half past five the next morning.
 *
 * The moment is taken in the timezone of `$after`. On the day the clocks go forward, an hour that
 * does not exist is moved on by PHP: 02:30 in Paris on the last Sunday of March is 03:30.
 *
 * @param string            $time  The time of day, `HH:MM`, from `00:00` to `23:59`.
 * @param DateTimeInterface $after The moment it must come strictly after.
 *
 * @return DateTimeImmutable The next moment it is `$time`.
 *
 * @throws InvalidArgumentException When `$time` is not a time of day written `HH:MM`.
 *
 * @example
 * ```php
 * use function oihana\core\date\nextTimeOfDay;
 *
 * $start = new DateTimeImmutable( '2026-11-01 23:00' ) ;
 *
 * nextTimeOfDay( '05:30' , $start ) ; // 2026-11-02 05:30
 * nextTimeOfDay( '23:30' , $start ) ; // 2026-11-01 23:30
 * nextTimeOfDay( '23:00' , $start ) ; // 2026-11-02 23:00 — now is not ahead
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.4.0
 */
function nextTimeOfDay( string $time , DateTimeInterface $after ) :DateTimeImmutable
{
    // `D`: without it, `$` also matches before a final line break, and "05:30\n" would pass.
    if( preg_match( '/^([01]\d|2[0-3]):([0-5]\d)$/D' , $time , $matches ) !== 1 )
    {
        throw new InvalidArgumentException( sprintf( 'A time of day is written HH:MM, from 00:00 to 23:59: "%s" is not.' , $time ) ) ;
    }

    $after = DateTimeImmutable::createFromInterface( $after ) ;
    $next  = $after->setTime( (int) $matches[ 1 ] , (int) $matches[ 2 ] ) ;

    return $next <= $after ? $next->modify( '+1 day' ) : $next ;
}
