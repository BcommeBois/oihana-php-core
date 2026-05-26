<?php

declare(strict_types=1);

namespace oihana\core\encoding ;

use InvalidArgumentException;
use Random\RandomException;

/**
 * Generates a cryptographically secure random token, encoded as a lowercase
 * hexadecimal string.
 *
 * The function reads `$bytes` of entropy from {@see random_bytes()} (a CSPRNG)
 * and encodes the result with {@see bin2hex()}. The output is safe to use in
 * URLs, logs, filenames, correlation/request IDs, JWT `jti`, refresh tokens
 * and similar contexts.
 *
 * Output length is exactly `2 * $bytes` characters. Default `$bytes = 32`
 * yields 256 bits of entropy → 64 characters.
 *
 * For shorter URL-friendly tokens at equivalent entropy, prefer
 * {@see randomBase64Url()}.
 *
 * @param int $bytes Number of random bytes to generate. Must be `>= 1`.
 *                   Defaults to 32 (256 bits of entropy).
 *
 * @return string Lowercase hexadecimal string of length `2 * $bytes`.
 *
 * @throws InvalidArgumentException If `$bytes` is less than 1.
 * @throws RandomException          If no source of randomness is available
 *                                  (propagated from {@see random_bytes()}).
 *
 * @example
 * ```php
 * use function oihana\core\encoding\randomHex;
 *
 * $requestId     = randomHex( 8  ) ; // 16 hex chars  ( 64 bits)
 * $correlationId = randomHex( 16 ) ; // 32 hex chars  (128 bits)
 * $refreshToken  = randomHex()      ; // 64 hex chars (256 bits, default)
 *
 * // Always lowercase hex
 * preg_match( '/^[0-9a-f]+$/' , $refreshToken ) ; // 1
 * ```
 *
 * @see hexEncode()        Encoder used internally.
 * @see randomBase64Url() Base64url variant (shorter at equivalent entropy).
 *
 * @package oihana\core\encoding
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function randomHex( int $bytes = 32 ) :string
{
    if ( $bytes < 1 )
    {
        throw new InvalidArgumentException
        (
            "randomHex() expects \$bytes >= 1, got $bytes"
        ) ;
    }

    return bin2hex( random_bytes( $bytes ) ) ;
}
