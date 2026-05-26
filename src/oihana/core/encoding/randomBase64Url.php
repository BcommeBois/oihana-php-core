<?php

declare(strict_types=1);

namespace oihana\core\encoding ;

use InvalidArgumentException;
use Random\RandomException;

/**
 * Generates a cryptographically secure random token, encoded in base64url
 * (RFC 4648 §5, URL-safe, no padding).
 *
 * The function reads `$bytes` of entropy from {@see random_bytes()} (a CSPRNG)
 * and encodes the result with {@see base64UrlEncode()}. The output is safe to
 * use in URLs, HTTP headers, filenames, JWT identifiers, OAuth state/nonce,
 * CSRF tokens and similar contexts.
 *
 * Output length is `ceil( $bytes * 4 / 3 )` characters (without padding).
 * Default `$bytes = 32` yields 256 bits of entropy → 43 characters, which is
 * the recommended size for cryptographic tokens.
 *
 * @param int $bytes Number of random bytes to generate. Must be `>= 1`.
 *                   Defaults to 32 (256 bits of entropy).
 *
 * @return string The base64url-encoded random token. Always a subset of
 *                `[A-Za-z0-9_-]`.
 *
 * @throws InvalidArgumentException If `$bytes` is less than 1.
 * @throws RandomException          If no source of randomness is available
 *                                  (propagated from {@see random_bytes()}).
 *
 * @example
 * ```php
 * use function oihana\core\encoding\randomBase64Url;
 *
 * $csrfToken     = randomBase64Url() ;        // 256 bits, 43 chars
 * $oauthState    = randomBase64Url( 16 ) ;    // 128 bits, 22 chars
 * $refreshToken  = randomBase64Url( 48 ) ;    // 384 bits, 64 chars
 *
 * // Always URL-safe and unpadded
 * preg_match( '/^[A-Za-z0-9_\-]+$/' , $csrfToken ) ; // 1
 * ```
 *
 * @see base64UrlEncode() Encoder used internally.
 * @see randomHex()       Hex-encoded variant.
 *
 * @package oihana\core\encoding
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function randomBase64Url( int $bytes = 32 ) :string
{
    if ( $bytes < 1 )
    {
        throw new InvalidArgumentException
        (
            "randomBase64Url() expects \$bytes >= 1, got $bytes"
        ) ;
    }

    return base64UrlEncode( random_bytes( $bytes ) ) ;
}
