<?php

namespace oihana\core\bits;

/**
 * Toggles a specific flag in a bitmask.
 *
 * If the flag is present, it will be removed; if absent, it will be added.
 *
 * @param int $mask The original bitmask.
 * @param int $flag The flag to toggle.
 *
 * @return int The new bitmask with the flag toggled.
 *
 * @example
 * ```php
 * use function oihana\core\bits\toggleFlag;
 *
 * const FLAG_A = 1 << 0; // 1
 * const FLAG_B = 1 << 1; // 2
 *
 * $mask = FLAG_A;        // 1
 * $mask = toggleFlag($mask, FLAG_B);
 * // $mask is now 3 (FLAG_A | FLAG_B)
 *
 * $mask = toggleFlag($mask, FLAG_A);
 * // $mask is now 2 (FLAG_B only)
 * ```
 *
 * @package oihana\core\bits
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function toggleFlag( int $mask , int $flag ) :int
{
    return $mask ^ $flag ;
}