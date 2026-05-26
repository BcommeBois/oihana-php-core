<?php

declare(strict_types=1);

namespace oihana\core\encoding ;

/**
 * Encodes binary data into a base64url string (RFC 4648 §5).
 *
 * The output uses the URL- and filename-safe alphabet (`-` and `_` instead of
 * `+` and `/`) and **omits** the trailing `=` padding, producing the canonical
 * URL-safe form used by JWT/JOSE, WebPush, signed URLs, etc.
 *
 * The function is binary-safe: any byte sequence is accepted, including
 * `null` bytes and arbitrary UTF-8.
 *
 * @param string $binary Raw bytes to encode. May be empty.
 *
 * @return string The base64url-encoded representation, without padding.
 *                Always a subset of `[A-Za-z0-9_-]`.
 *
 * @example
 * ```php
 * use function oihana\core\encoding\base64UrlEncode;
 *
 * echo base64UrlEncode( 'hello' ) ;
 * // Outputs: aGVsbG8
 *
 * echo base64UrlEncode( "\xFB\xFF" ) ;
 * // Outputs: -_8 (note the URL-safe '-' and '_', no '=' padding)
 *
 * // JWT-style usage
 * $header  = base64UrlEncode( json_encode( [ 'alg' => 'HS256' , 'typ' => 'JWT' ] ) ) ;
 * $payload = base64UrlEncode( json_encode( [ 'sub' => '42' , 'iat' => time() ] ) ) ;
 * $signing = $header . '.' . $payload ;
 * ```
 *
 * @see base64UrlDecode() For the inverse operation.
 *
 * @package oihana\core\encoding
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function base64UrlEncode( string $binary ) :string
{
    return rtrim( strtr( base64_encode( $binary ) , '+/' , '-_' ) , '=' ) ;
}
