<?php

namespace oihana\core\strings ;

/**
 * Generate a predicate expression as a string.
 *
 * This function builds a simple expression in the form:
 * `leftOperand operator rightOperand`.
 * If the operator is omitted, only the left operand is returned.
 *
 * Examples:
 * ```php
 * predicate('age', '>=', 18);          // "age >= 18"
 * predicate('status', 'is not null');  // "status is not null"
 * predicate('name');                    // "name"
 * ```
 *
 * @param mixed       $leftOperand  The left-hand value.
 * @param string|null $operator     The operator (optional, e.g., '==', '!=', '>=', 'is null').
 * @param mixed       $rightOperand The right-hand value (optional).
 *
 * @return string The resulting predicate expression.
 *
 * @package oihana\core\strings
 * @since 1.0.0
 * @author Marc Alcaraz
 */
function predicate( mixed $leftOperand , ?string $operator = null , mixed $rightOperand = null ) :string
{
    $expression = [ $leftOperand ] ;
    if( !is_null( $operator ) )
    {
        $expression[] = $operator ;
        if ( $rightOperand !== null )
        {
            $expression[] = $rightOperand;
        }
    }
    return implode( ' ' , $expression ) ;
}