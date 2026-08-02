<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns a copy of a date shifted backwards by a number of seconds.
 *
 * A new `DateTimeImmutable` is returned ; the source date is never modified. Equivalent
 * to `addSeconds( $date , -$seconds )` — see `addSeconds()` for the elapsed-time
 * semantics across a DST transition.
 *
 * @param DateTimeInterface $date    The source date.
 * @param int               $seconds The number of seconds to subtract.
 *
 * @return DateTimeImmutable A new immutable date shifted backwards by `$seconds` seconds.
 *
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\subSeconds;
 *
 * subSeconds( new DateTimeImmutable( '2026-01-01 00:01:30' ) , 90 ) ; // 2026-01-01 00:00:00
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function subSeconds( DateTimeInterface $date , int $seconds ): DateTimeImmutable
{
    return addSeconds( $date , -$seconds ) ;
}
