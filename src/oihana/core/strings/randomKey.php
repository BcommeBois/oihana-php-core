<?php

namespace oihana\core\strings ;

/**
 * Generates a random key string, optionally prefixed with a given string and separated by a custom separator.
 *
 * The random part is generated using `mt_rand()` which provides a pseudo-random integer.
 *
 * @param ?string $prefix Optional prefix to prepend to the key. If null or empty, no prefix is added.
 * @param string $separator Separator string between the prefix and the random number. Defaults to underscore '_'.
 * @return string The generated random key.
 *
 * @example
 * ```php
 * echo randomKey("user");       // Possible output: "user_123456789"
 * echo randomKey(null);         // Possible output: "987654321"
 * echo randomKey("order", "-"); // Possible output: "order-456789123"
 * ```
 */
function randomKey( ?string $prefix , string $separator = '_' ) :string
{
    return ( is_string( $prefix ) ? ( $prefix . $separator ) : '' ) . mt_rand() ;
}