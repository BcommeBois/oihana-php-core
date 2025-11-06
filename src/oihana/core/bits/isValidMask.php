<?php

namespace oihana\core\bits;

/**
 * Validates that a bitmask contains only allowed flags.
 *
 * @param int $mask      The bitmask to validate.
 * @param int $allowed   A bitmask of all valid flags.
 *
 * @return bool Returns true if all bits in $mask are present in $allowed, false otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\bits\isValidMask;
 *
 * const FLAG_A = 1 << 0; // 1
 * const FLAG_B = 1 << 1; // 2
 * const FLAG_C = 1 << 2; // 4
 * const ALL_FLAGS = FLAG_A | FLAG_B | FLAG_C; // 7
 *
 * $mask = FLAG_A | FLAG_B;
 * isValidMask($mask, ALL_FLAGS); // true
 *
 * $mask = FLAG_A | FLAG_B | (1 << 5);
 * isValidMask($mask, ALL_FLAGS); // false, bit 5 is invalid
 * ```
 *
 * @package oihana\core\bits
 * @author Marc Alcaraz (ekameleon)
 * @since 1.0.7
 */
function isValidMask(int $mask, int $allowed): bool
{
    return ($mask & ~$allowed) === 0;
}