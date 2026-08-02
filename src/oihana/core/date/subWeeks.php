<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted backwards by a number of weeks.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. Equivalent
 * to `addWeeks( $date , -$weeks )` — see `addWeeks()` for the calendar-arithmetic
 * semantics.
 *
 * @param DateTimeInterface $date  The source date.
 * @param int               $weeks The number of weeks to subtract.
 *
 * @return DateTimeImmutable A new immutable date shifted backwards by `$weeks` weeks.
 *
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\subWeeks;
 *
 * subWeeks( new DateTimeImmutable( '2026-01-15' ) , 2 ) ; // 2026-01-01
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function subWeeks( DateTimeInterface $date , int $weeks ): DateTimeImmutable
{
    return addWeeks( $date , -$weeks ) ;
}
