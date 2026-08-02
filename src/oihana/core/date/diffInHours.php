<?php

namespace oihana\core\date ;

use DateTimeInterface ;

/**
 * Returns the signed number of complete elapsed hours between two instants.
 *
 * Absolute-duration (elapsed-time) arithmetic, computed straight from the Unix
 * timestamps — unlike `diffInDays()`, which is calendar-aware, this counts real hours.
 * Across a DST transition the two can disagree on what looks like the same nominal
 * span : from `2026-03-28 00:00` to `2026-03-30 00:00` Europe/Paris is 2 calendar days
 * (`diffInDays()`) but only 47 real hours (`diffInHours()`), the spring-forward gap on
 * `2026-03-29` having shortened that day by one hour. The result is positive when `$b`
 * is later than `$a`, negative when it is earlier, truncated toward zero.
 *
 * @param DateTimeInterface $a The starting instant.
 * @param DateTimeInterface $b The ending instant.
 *
 * @return int The number of complete hours from `$a` to `$b` ; negative if `$b` is before `$a`.
 *
 * @example
 * ```php
 * use function oihana\core\date\diffInHours;
 *
 * diffInHours( new DateTimeImmutable( '2026-01-01 10:00:00' ) , new DateTimeImmutable( '2026-01-01 15:30:00' ) ) ; // 5
 * diffInHours( new DateTimeImmutable( '2026-01-01 15:30:00' ) , new DateTimeImmutable( '2026-01-01 10:00:00' ) ) ; // -5
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function diffInHours( DateTimeInterface $a , DateTimeInterface $b ): int
{
    return intdiv( $b->getTimestamp() - $a->getTimestamp() , 3600 ) ;
}
