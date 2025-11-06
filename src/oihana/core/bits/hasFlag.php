<?php

namespace oihana\core\bits;

/**
 * Checks whether a specific flag is set in a bitmask.
 *
 * This function is useful when working with bitwise flags.
 * You can combine multiple flags using the bitwise OR operator (`|`)
 * and then check if a particular flag is present in the combined mask.
 *
 * @param int $mask The bitmask value, potentially containing multiple flags combined with `|`.
 * @param int $flag The specific flag to check for in the mask.
 *
 * @return bool Returns `true` if the given flag is present in the mask, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\bits\hasFlag;
 *
 * const FLAG_A = 1 << 0;
 * const FLAG_B = 1 << 1;
 *
 * $mask = FLAG_A | FLAG_B;
 *
 * hasFlag($mask, FLAG_A); // true
 * hasFlag($mask, FLAG_B); // true
 * hasFlag($mask, 1 << 2); // false
 * ```
 *
 * @package oihana\core\bits
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function hasFlag( int $mask , int $flag ): bool
{
    return ( $mask & $flag ) !== 0 ;
}