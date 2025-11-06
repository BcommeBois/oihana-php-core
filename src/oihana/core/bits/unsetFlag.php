<?php

namespace oihana\core\bits;

/**
 * Unsets (removes) a specific flag from a bitmask.
 *
 * @param int $mask The original bitmask.
 * @param int $flag The flag to remove.
 *
 * @return int The new bitmask with the flag removed.
 *
 * @example
 * ```php
 * use function oihana\core\bits\unsetFlag;
 *
 * const FLAG_A = 1 << 0; // 1
 * const FLAG_B = 1 << 1; // 2
 *
 * $mask = FLAG_A | FLAG_B; // 3
 * $mask = unsetFlag($mask, FLAG_B);
 * // $mask is now 1 (FLAG_A only)
 * ```
 *
 * @package oihana\core\bits
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function unsetFlag(int $mask, int $flag): int
{
    return $mask & (~$flag);
}