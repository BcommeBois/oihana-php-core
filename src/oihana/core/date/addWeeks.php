<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted by a number of weeks.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. This is
 * calendar arithmetic, equivalent to `addDays( $date , $weeks * 7 )` : the wall-clock
 * time of day is preserved, so across a DST transition the shift can differ from the
 * same span expressed in hours (see `addHours()`). A negative `$weeks` shifts the date
 * backwards.
 *
 * @param DateTimeInterface $date  The source date.
 * @param int               $weeks The number of weeks to add (negative to subtract).
 *
 * @return DateTimeImmutable A new immutable date shifted by `$weeks` weeks.
 *
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\addWeeks;
 *
 * addWeeks( new DateTimeImmutable( '2026-01-01' ) , 2 )  ; // 2026-01-15
 * addWeeks( new DateTimeImmutable( '2026-01-01' ) , -1 ) ; // 2025-12-25
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function addWeeks( DateTimeInterface $date , int $weeks ): DateTimeImmutable
{
    return DateTimeImmutable::createFromInterface( $date )->modify( $weeks . ' weeks' ) ;
}
