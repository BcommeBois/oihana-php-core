<?php

namespace oihana\core\date ;

/**
 * Tells whether a year is a leap year, in the proleptic Gregorian calendar.
 *
 * Pure arithmetic — divisible by 4, except centuries, which must be divisible by 400 —
 * defined for any integer year, including zero and negative ones.
 *
 * @param int $year Any integer year.
 *
 * @return bool `true` if `$year` is a leap year, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\date\isLeapYear;
 *
 * isLeapYear( 2024 ) ; // true  (divisible by 4)
 * isLeapYear( 2026 ) ; // false
 * isLeapYear( 1900 ) ; // false (divisible by 100, not 400)
 * isLeapYear( 2000 ) ; // true  (divisible by 400)
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 */
function isLeapYear( int $year ): bool
{
    return $year % 4 === 0 && ( $year % 100 !== 0 || $year % 400 === 0 ) ;
}
