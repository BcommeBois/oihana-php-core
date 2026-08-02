<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;
use DateTimeZone ;

/**
 * Tells whether two instants fall on the same calendar day.
 *
 * The same physical instant can be a different calendar date depending on the timezone
 * it is read in — `2026-01-01 00:30:00 UTC` is still `2025-12-31` in `America/Los_Angeles`.
 * Both dates are converted to `$timezone` before their calendar date is compared ; it
 * defaults to the timezone carried by `$a`, so the common, no-argument call answers
 * "is `$b` the same day as `$a`, as seen from `$a`'s own timezone". Pass `$timezone`
 * explicitly to compare from a third, unrelated timezone instead.
 *
 * @param DateTimeInterface $a        The first instant.
 * @param DateTimeInterface $b        The second instant.
 * @param DateTimeZone|null $timezone The timezone the comparison is made in. Defaults to `$a`'s own timezone.
 *
 * @return bool `true` if `$a` and `$b` fall on the same calendar day in `$timezone`, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\date\isSameDay;
 *
 * $a = new DateTimeImmutable( '2026-01-01 01:00:00' , new DateTimeZone( 'UTC' ) );
 * $b = new DateTimeImmutable( '2026-01-01 20:00:00' , new DateTimeZone( 'UTC' ) );
 *
 * isSameDay( $a , $b ) ; // true — both January 1st in UTC, $a's own timezone (the default)
 *
 * // Compared from Tokyo (UTC+9) instead, $a is still Jan 1st (10:00) but $b has
 * // already rolled over to Jan 2nd (05:00) : same two instants, different verdict.
 * isSameDay( $a , $b , new DateTimeZone( 'Asia/Tokyo' ) ) ; // false
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function isSameDay( DateTimeInterface $a , DateTimeInterface $b , ?DateTimeZone $timezone = null ): bool
{
    $timezone ??= $a->getTimezone() ;

    $aInTimezone = DateTimeImmutable::createFromInterface( $a )->setTimezone( $timezone ) ;
    $bInTimezone = DateTimeImmutable::createFromInterface( $b )->setTimezone( $timezone ) ;

    return $aInTimezone->format( 'Y-m-d' ) === $bInTimezone->format( 'Y-m-d' ) ;
}
