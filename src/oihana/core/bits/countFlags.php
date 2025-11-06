<?php

namespace oihana\core\bits;

/**
 * Counts the number of active flags (bits set to 1) in a bitmask.
 *
 * This function is useful when you want to know how many flags are currently active.
 *
 * @param int $mask The bitmask to analyze.
 *
 * @return int The number of bits set to 1 in the mask.
 *
 * @example
 * ```php
 * use function oihana\core\bits\countFlags;
 *
 * const FLAG_A = 1 << 0; // 1
 * const FLAG_B = 1 << 1; // 2
 * const FLAG_C = 1 << 2; // 4
 *
 * $mask = FLAG_A | FLAG_B;  // 3
 * echo countFlags($mask);   // Outputs: 2
 *
 * $mask = FLAG_A | FLAG_B | FLAG_C; // 7
 * echo countFlags($mask);           // Outputs: 3
 *
 * $mask = 0;                        // no flags
 * echo countFlags($mask);           // Outputs: 0
 * ```
 *
 * @package oihana\core\bits
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function countFlags( int $mask ) :int
{
    return substr_count( decbin( $mask ) , '1' ) ;
}