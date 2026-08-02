<?php

namespace oihana\core\date ;

use DateMalformedStringException ;
use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Returns the start of the day preceding a reference date.
 *
 * `startOfDay( addDays( $now , -1 ) )` — always `00:00:00.000000` on the previous
 * calendar day, regardless of the time of day `$now` carries. The timezone of the
 * returned value is the one carried by `$now`.
 *
 * @param DateTimeInterface|null $now The reference date. Defaults to the current date/time.
 *
 * @return DateTimeImmutable The start of the day before `$now`.
 *
 * @throws DateMalformedStringException Never thrown in practice (the modifier is always well-formed).
 *
 * @example
 * ```php
 * use function oihana\core\date\yesterday;
 *
 * yesterday( new DateTimeImmutable( '2026-03-15 14:30:45' ) ) ; // 2026-03-14 00:00:00.000000
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function yesterday( ?DateTimeInterface $now = null ): DateTimeImmutable
{
    return startOfDay( addDays( $now ?? new DateTimeImmutable() , -1 ) ) ;
}
