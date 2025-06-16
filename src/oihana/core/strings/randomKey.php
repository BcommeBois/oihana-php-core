<?php

namespace oihana\core\strings ;

use oihana\enums\Char;

/**
 * Generates a random key.
 * @param ?string $prefix The expression to format.
 * @param string $separator The separator characters/expression of the random key.
 * @return string The new random key.
 */
function randomKey( ?string $prefix , string $separator = Char::UNDERLINE ) :string
{
    return ( is_string( $prefix ) ? ( $prefix . $separator ) : '' ) . mt_rand() ;
}