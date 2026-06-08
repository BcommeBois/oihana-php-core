<?php

namespace oihana\core\cbor ;

use Beau\CborPHP\CborEncoder;
use Beau\CborPHP\exceptions\CborException;

use Closure;
use RuntimeException;
use Throwable;

use function oihana\core\objects\toAssociativeArray;

/**
 * Encodes any data into a CBOR binary string.
 * Automatically converts objects and arrays to pure associative arrays.
 *
 * This function will attempt to convert objects/arrays into associative arrays
 * before encoding. If encoding fails due to invalid data, a `RuntimeException`
 * will be thrown with an appropriate HTTP-style code:
 * - `422` (Unprocessable Entity) if the data cannot be encoded (client-side issue)
 * - `500` (Internal Server Error) for unexpected failures (server-side issue)
 *
 * @param mixed        $data The data to encode (scalar, array, or object).
 * @param Closure|null $replacer Optional callback applied to each encoded value: fn($key, $value)
 *
 * @return string The binary CBOR string.
 *
 * @example
 * ```php
 * use function oihana\core\cbor\cbor_encode;
 *
 * $data =
 * [
 *     'name' => 'Alice',
 *     'age'  => 30,
 *     'tags' => ['developer', 'php']
 * ];
 *
 * try
 * {
 *     $cbor = cbor_encode($data);
 *     echo "CBOR encoded data length: " . strlen($cbor);
 * }
 * catch (RuntimeException $e)
 * {
 *     echo "Encoding failed (code {$e->getCode()}): " . $e->getMessage();
 * }
 * ```
 *
 * @throws RuntimeException On CBOR encoding failure.
 *
 * @see toAssociativeArray() for details.
 *
 * @package oihana\core\cbor
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function cbor_encode( mixed $data , ?Closure $replacer = null ):string
{
    try
    {
        if ( is_array( $data ) || is_object( $data ) )
        {
            $data = toAssociativeArray( $data , strict:true ) ;
        }

        // The encoder's default replacer returns unsupported values unchanged, so it recurses
        // forever (e.g. on a resource) instead of failing — only Xdebug's nesting limit turns
        // that into a catchable error. Reject any value the encoder cannot handle so the failure
        // surfaces as the 500 below, with or without Xdebug.
        $replacer ??= fn( mixed $key , mixed $value ) : mixed
            => throw new RuntimeException( 'unsupported value of type ' . get_debug_type( $value ) , 500 ) ;

        return CborEncoder::encode( $data , $replacer ) ;
    }
    // defensive: the encoder raises generic Throwable, not CborException
    // @codeCoverageIgnoreStart
    catch ( CborException $e )
    {
        throw new RuntimeException
        (
            message  : 'CBOR encoding failed: ' . $e->getMessage(),
            code     : 422 , // Unprocessable Entity
            previous : $e
        );
    }
    // @codeCoverageIgnoreEnd
    catch ( Throwable $e )
    {
        throw new RuntimeException
        (
            message  : 'CBOR encoding failed: ' . $e->getMessage(),
            code     : 500 , // Internal Server Error
            previous : $e
        );
    }
}