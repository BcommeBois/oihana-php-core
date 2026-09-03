<?php

namespace oihana\core\arrays ;

/**
 * Tells what left, what showed up, and what stayed, between two lists of identifiers.
 *
 * Meant to diff two runs of the same listing — e.g. the ids returned by a previous
 * fetch versus the current one — and answer three questions at once : what closed,
 * what appeared, and what stayed.
 *
 * @param array<int, string> $before The identifiers of a previous run.
 * @param array<int, string> $now    The identifiers of this run.
 *
 * @return array{0: array<int, string>, 1: array<int, string>, 2: array<int, string>}
 * A 3-tuple `[ $removed , $added , $kept ]`, each re-indexed from `0`.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\delta;
 *
 * [ $removed , $added , $kept ] = delta( [ 'a' , 'b' , 'c' ] , [ 'b' , 'c' , 'd' ] ) ;
 *
 * print_r( $removed ) ; // [ 'a' ]
 * print_r( $added )   ; // [ 'd' ]
 * print_r( $kept )    ; // [ 'b' , 'c' ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function delta( array $before , array $now ) :array
{
    return
    [
        array_values( array_diff( $before , $now ) ) ,
        array_values( array_diff( $now , $before ) ) ,
        array_values( array_intersect( $now , $before ) ) ,
    ] ;
}
