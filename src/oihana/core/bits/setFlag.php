<?php

namespace oihana\core\bits;

/**
 * Sets a specific flag in a bitmask.
 *
 * @param int $mask The original bitmask.
 * @param int $flag The flag to set.
 *
 * @return int The new bitmask with the flag set.
 *
 * @example
 * ```php
 * use function oihana\core\bits\setFlag;
 *
 * const FLAG_A = 1 << 0; // 1
 * const FLAG_B = 1 << 1; // 2
 *
 * $mask = FLAG_A;          // 1
 * $mask = setFlag($mask, FLAG_B);
 * // $mask is now 3 (FLAG_A | FLAG_B)
 * ```
 *
 * @package oihana\core\bits
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function setFlag( int $mask , int $flag ) :int
{
    return $mask | $flag ;
}