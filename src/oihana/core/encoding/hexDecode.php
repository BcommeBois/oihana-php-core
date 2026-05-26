<?php

declare(strict_types=1);

namespace oihana\core\encoding ;

/**
 * Decodes a hexadecimal string into raw bytes.
 *
 * Strict, warning-free counterpart of {@see hex2bin()} :
 *
 * - Accepts both lowercase and uppercase digits (`[0-9a-fA-F]`).
 * - Requires an **even** number of characters ; an odd length yields `false`.
 * - Rejects any character outside the hex alphabet (including whitespace and
 *   non-ASCII bytes) with `false` instead of PHP's native warning.
 * - The empty string decodes to the empty string.
 *
 * The function does not raise exceptions ; it returns `false` on any invalid
 * input, mirroring the contract of {@see base64UrlDecode()}.
 *
 * Note: when comparing a decoded value to a known secret (e.g. an HMAC tag),
 * always use {@see hash_equals()} for constant-time comparison.
 *
 * @param string $value The hexadecimal string to decode. May be empty.
 *
 * @return string|false The decoded raw bytes, or `false` if `$value` is not a
 *                      valid hexadecimal string of even length.
 *
 * @example
 * ```php
 * use function oihana\core\encoding\hexDecode;
 *
 * echo hexDecode( '616263' ) ;          // "abc"
 * echo hexDecode( '00FF'   ) ;          // "\x00\xFF" (uppercase accepted)
 * echo hexDecode( ''       ) ;          // ""
 *
 * var_dump( hexDecode( '6'      ) ) ;   // bool(false) — odd length
 * var_dump( hexDecode( '6Z'     ) ) ;   // bool(false) — invalid char
 * var_dump( hexDecode( '61 62'  ) ) ;   // bool(false) — whitespace forbidden
 * ```
 *
 * @see hexEncode() For the inverse operation.
 *
 * @package oihana\core\encoding
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function hexDecode( string $value ) :string|false
{
    if ( $value === '' )
    {
        return '' ;
    }

    if ( ( strlen( $value ) & 1 ) === 1 )
    {
        return false ;
    }

    if ( preg_match( '/^[0-9a-fA-F]+$/' , $value ) !== 1 )
    {
        return false ;
    }

    return hex2bin( $value ) ;
}
