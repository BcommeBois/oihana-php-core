<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Append one or more suffix strings to the given source string, then normalize the result.
 *
 * If the source string is null, returns an empty string.
 * If multiple suffixes are provided, they are concatenated before appending.
 * The resulting string is normalized using Unicode Normalization Form C (NFC).
 *
 * @param ?string  $source    The original string or null.
 * @param  string  ...$suffix One or more suffix strings to append.
 *
 * @return string The normalized concatenation of source and suffix(es).
 *
 * @throws InvalidArgumentException If the resulting string is invalid UTF-8 or cannot be normalized.
 *
 * @example
 * ```php
 * use function oihana\core\strings\append;
 *
 * echo append( 'hello', ' ', 'world'); // Outputs: "hello world"
 * echo append( null, 'foo');           // Outputs: "foo"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function append( ?string $source , string ...$suffix ) :string
{
    if ( $source === null )
    {
        $source = '' ;
    }

    $source .= ( count($suffix) <= 1 ? ( $suffix[0] ?? '' ) : implode('' , $suffix ) );

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