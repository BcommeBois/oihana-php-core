<?php

namespace oihana\core\strings ;

/**
 * Generates a random key.
 * @param ?string $prefix The expression to format.
 * @param string $separator The separator characters/expression of the random key.
 * @return string The new random key.
 */
function randomKey( ?string $prefix , string $separator = '_' ) :string
{
    return ( is_string( $prefix ) ? ( $prefix . $separator ) : '' ) . mt_rand() ;
}