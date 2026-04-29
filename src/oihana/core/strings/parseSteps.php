<?php

declare(strict_types=1);

namespace oihana\core\strings;

use InvalidArgumentException;

/**
 * Parses a step-range expression into a sorted, deduplicated list of integer
 * steps in `[1, $maxStep]`.
 *
 * Designed for CLI commands that need to selectively run a subset of their
 * internal steps, e.g. integration test commands with an `--step=N` option.
 *
 * Accepted syntax:
 * - `null` or `''` (empty)  → all steps `[1..maxStep]` (default).
 * - `'all'` or `'*'`        → same as above (explicit "all").
 * - `'N'`                   → `[N]` (single step).
 * - `'N1-N2'`               → `[N1, N1+1, …, N2]` (closed range, inclusive).
 * - `'-N'`                  → `[1, 2, …, N]` (open-left half range).
 * - `'N-'`                  → `[N, N+1, …, maxStep]` (open-right half range).
 * - `'A,B,C-D,E'`           → comma-separated tokens, each parsed independently
 *                             and merged. Order is irrelevant ; the result is
 *                             always sorted with duplicates removed.
 *
 * Whitespace around tokens / hyphens is allowed and stripped.
 *
 * Invalid inputs raise `InvalidArgumentException`. The helper rejects:
 * - empty tokens (e.g. `'1,,3'`),
 * - non-numeric tokens (e.g. `'abc'`),
 * - bare `'-'` (missing both bounds),
 * - inverted ranges (`'6-4'`),
 * - out-of-range numbers (`< 1` or `> $maxStep`).
 *
 * @param string|null $input   The step expression. `null` and `''` mean "all".
 * @param int         $maxStep The maximum step number (inclusive). Used to
 *                             expand `all` / `*` and open-right ranges (`N-`),
 *                             and to validate upper bounds.
 *
 * @return int[] Sorted, deduplicated list of step numbers, all in `[1, $maxStep]`.
 *
 * @throws InvalidArgumentException When the input cannot be parsed or contains
 *                                  out-of-range / inverted / malformed tokens.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 *
 * @example
 * ```php
 * parseSteps( null      , 6 ) ; // [1, 2, 3, 4, 5, 6]
 * parseSteps( 'all'     , 6 ) ; // [1, 2, 3, 4, 5, 6]
 * parseSteps( '*'       , 6 ) ; // [1, 2, 3, 4, 5, 6]
 * parseSteps( '4'       , 6 ) ; // [4]
 * parseSteps( '4-6'     , 6 ) ; // [4, 5, 6]
 * parseSteps( '-4'      , 6 ) ; // [1, 2, 3, 4]
 * parseSteps( '5-'      , 6 ) ; // [5, 6]
 * parseSteps( '1,3,5-6' , 6 ) ; // [1, 3, 5, 6]
 * ```
 */
function parseSteps( ?string $input , int $maxStep ) :array
{
    if ( $maxStep < 1 )
    {
        throw new InvalidArgumentException( "maxStep must be >= 1, got $maxStep" ) ;
    }

    $expression = $input !== null ? trim( $input ) : '' ;

    if ( $expression === '' || $expression === 'all' || $expression === '*' )
    {
        return range( 1 , $maxStep ) ;
    }

    $steps = [] ;

    foreach ( explode( ',' , $expression ) as $token )
    {
        $token = trim( $token ) ;

        if ( $token === '' )
        {
            throw new InvalidArgumentException
            (
                "Empty token in step expression '$expression'"
            ) ;
        }

        if ( str_contains( $token , '-' ) )
        {
            [ $leftRaw , $rightRaw ] = explode( '-' , $token , 2 ) ;
            $left  = trim( $leftRaw  ) ;
            $right = trim( $rightRaw ) ;

            if ( $left === '' && $right === '' )
            {
                throw new InvalidArgumentException
                (
                    "Range '$token' is missing both bounds in step expression '$expression'"
                ) ;
            }

            $from = $left  === '' ? 1        : parseStepNumber( $left  , $token , $expression , $maxStep ) ;
            $to   = $right === '' ? $maxStep : parseStepNumber( $right , $token , $expression , $maxStep ) ;

            if ( $from > $to )
            {
                throw new InvalidArgumentException
                (
                    "Inverted range '$token' (from $from to $to) in step expression '$expression'"
                ) ;
            }

            for ( $i = $from ; $i <= $to ; $i++ )
            {
                $steps[] = $i ;
            }
        }
        else
        {
            $steps[] = parseStepNumber( $token , $token , $expression , $maxStep ) ;
        }
    }

    $steps = array_values( array_unique( $steps ) ) ;
    sort( $steps ) ;

    return $steps ;
}

/**
 * Validates and parses a single step number in the context of the surrounding
 * expression. Internal helper for {@see parseSteps()} ; not part of the public
 * API but namespaced so the test suite can target it directly when useful.
 *
 * @param string $value      The numeric token to parse (already trimmed).
 * @param string $token      The full token containing `$value` (used in error
 *                           messages to point at the offending range / number).
 * @param string $expression The original full expression (used in error messages).
 * @param int    $maxStep    The maximum allowed step number (inclusive).
 *
 * @return int The parsed step number.
 *
 * @throws InvalidArgumentException When `$value` is not a positive integer or
 *                                  falls outside `[1, $maxStep]`.
 *
 * @package oihana\api\helpers
 * @author  Marc Alcaraz
 *
 * @internal
 */
function parseStepNumber( string $value , string $token , string $expression , int $maxStep ) :int
{
    if ( !preg_match( '/^\d+$/' , $value ) )
    {
        throw new InvalidArgumentException
        (
            "Invalid number '$value' in token '$token' of step expression '$expression'"
        ) ;
    }

    $n = (int) $value ;

    if ( $n < 1 || $n > $maxStep )
    {
        throw new InvalidArgumentException
        (
            "Step $n out of range [1, $maxStep] in token '$token' of step expression '$expression'"
        ) ;
    }

    return $n ;
}
