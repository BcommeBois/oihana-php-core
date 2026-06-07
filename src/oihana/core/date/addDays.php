<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted by a number of days.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. A negative
 * `$days` shifts the date backwards.
 *
 * @param DateTimeInterface $date The source date.
 * @param int               $days The number of days to add (negative to subtract).
 *
 * @return DateTimeImmutable A new immutable date shifted by `$days` days.
 *
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\addDays;
 *
 * addDays( new DateTimeImmutable( '2026-01-01' ) , 5 )  ; // 2026-01-06
 * addDays( new DateTimeImmutable( '2026-01-01' ) , -1 ) ; // 2025-12-31
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function addDays( DateTimeInterface $date , int $days ): DateTimeImmutable
{
    return DateTimeImmutable::createFromInterface( $date )->modify( $days . ' days' ) ;
}
