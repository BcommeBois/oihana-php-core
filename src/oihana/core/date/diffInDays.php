<?php

namespace oihana\core\date ;

use DateTimeInterface ;

/**
 * Returns the signed number of calendar days between two instants.
 *
 * `DateTimeInterface::diff()` is a trap for this : its `DateInterval::$d` is only the
 * day-of-month component of a `y`/`m`/`d`/`h`/`i`/`s` breakdown, which resets every
 * month — over a 34-day gap it reads `3`, not `34`. This function reads the interval's
 * `$days` instead (the true total, calendar-aware — a day that has 23 or 25 hours across
 * a DST transition still counts as one day) and applies `$invert` as the sign, so the
 * result is positive when `$b` is later than `$a`, negative when it is earlier.
 *
 * @param DateTimeInterface $a The starting instant.
 * @param DateTimeInterface $b The ending instant.
 *
 * @return int The number of calendar days from `$a` to `$b` ; negative if `$b` is before `$a`.
 *
 * @example
 * ```php
 * use function oihana\core\date\diffInDays;
 *
 * diffInDays( new DateTimeImmutable( '2026-01-01' ) , new DateTimeImmutable( '2026-02-04' ) ) ; // 34
 * diffInDays( new DateTimeImmutable( '2026-02-04' ) , new DateTimeImmutable( '2026-01-01' ) ) ; // -34
 * diffInDays( new DateTimeImmutable( '2026-01-01' ) , new DateTimeImmutable( '2026-01-01' ) ) ; // 0
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function diffInDays( DateTimeInterface $a , DateTimeInterface $b ): int
{
    $interval = $a->diff( $b ) ;
    return $interval->invert ? -$interval->days : $interval->days ;
}
