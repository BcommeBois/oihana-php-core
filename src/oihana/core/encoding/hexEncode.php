<?php

declare(strict_types=1);

namespace oihana\core\encoding ;

/**
 * Encodes binary data into a lowercase hexadecimal string.
 *
 * Thin wrapper around {@see bin2hex()} provided for API symmetry with the
 * other helpers of this package ({@see hexDecode()}, {@see base64UrlEncode()}).
 *
 * The function is binary-safe and always produces lowercase output, which
 * matches the convention used for hashes, tokens and HMAC tags across the
 * Oihana ecosystem.
 *
 * @param string $binary Raw bytes to encode. May be empty.
 *
 * @return string A string of `[0-9a-f]` characters, twice as long as `$binary`.
 *
 * @example
 * ```php
 * use function oihana\core\encoding\hexEncode;
 *
 * echo hexEncode( 'abc' ) ;        // "616263"
 * echo hexEncode( "\x00\xFF" ) ;   // "00ff"
 * echo hexEncode( '' ) ;           // ""
 * ```
 *
 * @see hexDecode() For the inverse operation.
 *
 * @package oihana\core\encoding
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function hexEncode( string $binary ) :string
{
    return bin2hex( $binary ) ;
}
