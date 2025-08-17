<?php

namespace oihana\core\strings ;

use function oihana\core\arrays\clean;

/**
 * Generate a complex logical expression with multiple predicates.
 *
 * @param array|null $conditions      List of predicate expressions.
 * @param string     $logicalOperator The logical operator to join predicates (e.g., 'AND', 'OR').
 * @param bool       $useParentheses  Whether to wrap the result in parentheses.
 *
 * @return string|null The combined expression or null if empty.
 *
 * @example
 * ```php
 * $predicates = [
 *     $this->predicate('a', '==', 1),
 *     $this->predicate('b', '>', 5)
 * ];
 * $this->predicates( $predicates , 'AND' , true ) ; // '(a == 1 AND b > 5)'
 * ```
 *
 * @package oihana\core\strings
 * @since 1.0.0
 * @author Marc Alcaraz
 */
function predicates( ?array $conditions , string $logicalOperator , bool $useParentheses = false , bool $spacify = false ) :?string
{
    $conditions = clean( $conditions ?? [] ) ;

    if ( empty( $conditions ) )
    {
        return null;
    }

    $operator = $spacify ? " $logicalOperator " : $logicalOperator;

    return betweenParentheses( $conditions , $useParentheses , $operator ) ;
}