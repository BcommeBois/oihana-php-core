<?php

namespace oihana\core\bits;

/**
 * Checks whether **all** specified flags are set in a bitmask.
 *
 * This function is useful when you want to ensure that a set of flags
 * are all active within a bitmask.
 *
 * @param int $mask  The original bitmask, potentially containing multiple flags combined with `|`.
 * @param int $flags The flags to check (can be a single flag or multiple combined with `|`).
 *
 * @return bool Returns `true` if all the given flags are present in the mask, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\bits\hasAllFlags;
 *
 * const FLAG_A = 1 << 0; // 1
 * const FLAG_B = 1 << 1; // 2
 * const FLAG_C = 1 << 2; // 4
 *
 * $mask = FLAG_A | FLAG_B;  // 3
 *
 * hasAllFlags($mask, FLAG_A);           // true
 * hasAllFlags($mask, FLAG_A | FLAG_B); // true
 * hasAllFlags($mask, FLAG_A | FLAG_C); // false
 * ```
 *
 * @package oihana\core\bits
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function hasAllFlags( int $mask, int $flags ) :bool
{
    return ( $mask & $flags ) === $flags ;
}