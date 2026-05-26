<?php

declare(strict_types=1);

namespace oihana\core\encoding ;

/**
 * Decodes a base64url-encoded string (RFC 4648 §5).
 *
 * The decoder is **permissive on padding** but **strict on alphabet**:
 *
 * - Inputs in canonical URL-safe form (no trailing `=`) are accepted.
 * - Inputs that still carry the standard padding (`=`, `==`) are also accepted,
 *   for interoperability with implementations that emit padded base64url.
 * - Any character outside `[A-Za-z0-9_\-]` (or `=` only as trailing padding)
 *   causes the function to return `false`. This includes `+`, `/`, whitespace,
 *   newlines, control characters and any non-ASCII byte.
 *
 * The function is binary-safe: it operates on ASCII bytes regardless of the
 * current `mbstring.func_overload` setting or locale.
 *
 * The function does **not** raise exceptions ; it mirrors the contract of
 * {@see base64_decode()} and returns `false` on any invalid input.
 *
 * Note: when comparing a decoded value to a known secret (e.g. an HMAC tag),
 * always use a constant-time comparison such as {@see hash_equals()} ; this
 * function itself does not perform any cryptographic comparison.
 *
 * @param string $value The base64url string to decode. May be empty.
 *
 * @return string|false The decoded raw bytes, or `false` if `$value` is not a
 *                      valid base64url string.
 *
 * @example
 * ```php
 * use function oihana\core\encoding\base64UrlDecode;
 *
 * echo base64UrlDecode( 'aGVsbG8' ) ;          // "hello"  (no padding)
 * echo base64UrlDecode( 'aGVsbG8=' ) ;         // "hello"  (with padding, tolerated)
 * echo base64UrlDecode( '-_8' ) ;              // "\xFB\xFF"
 *
 * var_dump( base64UrlDecode( 'aGVsbG8+' ) ) ;  // bool(false) — '+' is not URL-safe
 * var_dump( base64UrlDecode( "aGVs\nbG8" ) ) ; // bool(false) — whitespace forbidden
 * var_dump( base64UrlDecode( 'aGVsbG8é' ) ) ;  // bool(false) — non-ASCII forbidden
 * ```
 *
 * @see base64UrlEncode() For the inverse operation.
 *
 * @package oihana\core\encoding
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function base64UrlDecode( string $value ) :string|false
{
    if ( $value === '' )
    {
        return '' ;
    }

    if ( preg_match( '/^[A-Za-z0-9_\-]+={0,2}$/' , $value ) !== 1 )
    {
        return false ;
    }

    $padded = $value . str_repeat( '=' , ( 4 - strlen( $value ) % 4 ) % 4 ) ;

    return base64_decode( strtr( $padded , '-_' , '+/' ) , true ) ;
}
