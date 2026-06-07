<?php

namespace oihana\core\date ;

use DateTimeImmutable ;
use DateTimeInterface ;

/**
 * Tells whether a date is strictly in the future.
 *
 * @param DateTimeInterface      $date The date to test.
 * @param DateTimeInterface|null $now  The reference "now". Defaults to the current date/time.
 *
 * @return bool `true` if `$date` is strictly after `$now`, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\date\isFuture;
 *
 * isFuture( new DateTimeImmutable( '2999-01-01' ) ) ; // true
 * isFuture( new DateTimeImmutable( '2000-01-01' ) ) ; // false
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function isFuture( DateTimeInterface $date , ?DateTimeInterface $now = null ): bool
{
    $now ??= new DateTimeImmutable() ;
    return $date > $now ;
}
