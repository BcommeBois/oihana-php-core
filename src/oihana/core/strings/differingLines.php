<?php

namespace oihana\core\strings ;

/**
 * Counts how far apart two texts are, in lines: the lines found on one side and not on the other.
 *
 * The lines are compared as a multiset, **without regard to their order**: a line present twice on
 * one side and once on the other counts one, a line changed counts two (the old one and the new
 * one), and lines that only moved count nothing. The answer is zero only when the two texts are
 * identical, byte for byte; any other difference — lines swapped, a trailing blank, a final line
 * break — counts one at least, so "different" is never reported as zero.
 *
 * Meant as a size, not a diff: "this copy differs from the reference by 12 lines" is enough to
 * tell a cosmetic change from a rewrite, without showing either.
 *
 * @param string $a The first text, e.g. the reference.
 * @param string $b The second text, e.g. the copy.
 *
 * @return int The number of lines on one side and not on the other; 1 at least when the texts differ.
 *
 * @example
 * ```php
 * use function oihana\core\strings\differingLines;
 *
 * differingLines( "a\nb\n" , "a\nb\n" ) ;    // 0
 * differingLines( "a\nb\n" , "a\nc\n" ) ;    // 2 — b gone, c come
 * differingLines( "a\n"    , "a\nb\n" ) ;    // 1
 * differingLines( "a\nb\n" , "b\na\n" ) ;    // 1 — the same lines, moved
 * differingLines( "a\n"    , "a \n" ) ;      // 2 — a trailing blank is a difference
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.4.0
 */
function differingLines( string $a , string $b ) :int
{
    if ( $a === $b )
    {
        return 0 ;
    }

    $first  = array_count_values( explode( "\n" , $a ) ) ;
    $second = array_count_values( explode( "\n" , $b ) ) ;

    $lines = 0 ;

    foreach ( $first + $second as $line => $ignored )
    {
        $lines += abs( ( $first[ $line ] ?? 0 ) - ( $second[ $line ] ?? 0 ) ) ;
    }

    return max( 1 , $lines ) ;
}
