<?php

namespace oihana\core\cbor ;

use Beau\CborPHP\CborDecoder;
use Beau\CborPHP\exceptions\CborException;

use Closure;
use RuntimeException;
use Throwable;

/**
 * Decodes a CBOR binary string into a PHP normalized structure (array, scalar, etc.).
 *
 * This function will attempt to decode CBOR data. If decoding fails due to invalid
 * CBOR content, a `RuntimeException` will be thrown with an HTTP-style code:
 * - `422` (Unprocessable Entity) if the CBOR data is invalid or malformed
 * - `500` (Internal Server Error) for unexpected failures
 *
 * @param string       $data     The binary CBOR data
 * @param Closure|null $replacer Optional callback applied to each decoded value: fn($key, $value)
 *
 * @return mixed The decoded PHP data (array, int, string, etc.)
 *
 * @throws RuntimeException On CBOR decoding failure
 *
 * @example
 * ```php
 * use function oihana\core\cbor\cbor_decode;
 *
 * $cbor = "\xA2\x64name\x65Alice\x63age\x18\x1E";
 * // CBOR encoded map: ['name'=>'Alice','age'=>30]
 *
 * try
 * {
 *     $decoded = cbor_decode($cbor);
 *     print_r($decoded);
 * }
 * catch (RuntimeException $e)
 * {
 *     echo "Decoding failed (code {$e->getCode()}): " . $e->getMessage();
 * }
 *
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 * @package oihana\core\json
 */
function cbor_decode( string $data , ?Closure $replacer = null ) :mixed
{
    try
    {
        return CborDecoder::decode( $data , $replacer ) ;
    }
    catch ( CborException $e )
    {
        throw new RuntimeException
        (
            message  : 'CBOR decoding failed: ' . $e->getMessage(),
            code     : 422 , // Unprocessable Entity
            previous : $e
        );
    }
    catch ( Throwable $e )
    {
        throw new RuntimeException
        (
            message  : 'CBOR decoding failed: ' . $e->getMessage(),
            code     : 500 , // Internal Server Error
            previous : $e
        );
    }
}