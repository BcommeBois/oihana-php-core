<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Prepend one or more prefix strings to the given source string, then normalize the result.
 *
 * If the source string is null, returns an empty string.
 * If multiple prefixes are provided, they are concatenated before prepending.
 * The resulting string is normalized using Unicode Normalization Form C (NFC).
 *
 * @param ?string  $source    The original string or null.
 * @param  string  ...$prefix One or more prefix strings to prepend.
 *
 * @return string The normalized concatenation of prefix(es) and source.
 *
 * @throws InvalidArgumentException If the resulting string is invalid UTF-8 or cannot be normalized.
 *
 * @example
 * ```php
 * use function oihana\core\strings\prepend;
 *
 * echo prepend( 'world', 'hello', ' ' ); // Outputs: "hello world"
 * echo prepend( null, 'foo');             // Outputs: "foo"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function prepend( ?string $source , string ...$prefix ) :string
{
    if ( $source === null )
    {
        $source = '' ;
    }

    $source = ( count($prefix) <= 1 ? ( $prefix[0] ?? '' ) : implode('' , $prefix ) ) . $source ;

    if ( normalizer_is_normalized( $source ) )
    {
        return $source ;
    }

    $string = normalizer_normalize( $source ) ;

    if ( $string === false )
    {
        throw new InvalidArgumentException('Invalid UTF-8 string.' ) ;
    }

    return $string ;
}