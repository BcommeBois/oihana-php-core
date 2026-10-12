<?php

namespace oihana\core\arrays ;

/**
 * Returns the given value when it is an array, and an empty array when it is not.
 *
 * Reach for this when a value of unknown shape — a decoded payload, an option
 * read from a configuration, a field of a response — has to be read as a record
 * or a list : what is not an array becomes **nothing to read**, so the reads that
 * follow answer `null` instead of tripping over a scalar.
 *
 * ⚠️ This is **not** {@see toArray()}, which *wraps* what it is given instead of
 * dropping it : `toArray( null )` answers `[ null ]` — a list holding one item —
 * where this function answers `[]`. The two read almost the same at a call site
 * and behave in opposite ways, so pick on the question being asked :
 *
 * - « read this as an array, or give me nothing » → `arrayOrEmpty()`
 * - « make a list out of this lone value »        → `toArray()`
 *
 * An array is returned untouched, keys, order and emptiness included.
 *
 * @param mixed $value The value to read as an array.
 *
 * @return array<int|string, mixed> The value itself when it is an array, an empty array otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\arrayOrEmpty;
 *
 * $a = arrayOrEmpty( [ 'name' => 'Nemo' , 'age' => 7 ] ) ;
 * print_r( $a ); // [ 'name' => 'Nemo' , 'age' => 7 ]
 *
 * $b = arrayOrEmpty( null ) ;
 * print_r( $b ); // []
 *
 * $c = arrayOrEmpty( 'not a record' ) ;
 * print_r( $c ); // []
 *
 * // Reading a field that may or may not be a list, without naming it twice.
 * $tags = arrayOrEmpty( $document[ 'tags' ] ?? null ) ;
 *
 * // The contrast with toArray(), on the very same value.
 * print_r( arrayOrEmpty( 42 ) ); // []
 * print_r( toArray( 42 ) );      // [ 42 ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function arrayOrEmpty( mixed $value ) :array
{
    return is_array( $value ) ? $value : [] ;
}
