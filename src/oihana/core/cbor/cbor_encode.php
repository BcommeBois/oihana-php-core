<?php

namespace oihana\core\cbor ;

use CBOR\Encoder;
use function oihana\core\objects\toAssociativeArray;

/**
 * Encodes any data into a CBOR binary string.
 *
 * Automatically converts objects and arrays to pure associative arrays
 * using toAssociativeArray() before encoding, ensuring complex objects
 * are serialized correctly.
 *
 * @param mixed                    $data   The data to encode (scalar, array, or object).
 * @param string|array|object|null $helper Optional internal encoder helper.
 *                                         This value is resolved into a callable using {@see resolveCallable()}.
 *                                         Supported forms:
 *                                         - Closure or invokable object
 *                                         - Callable array: [$object, 'method'] or ['Class', 'method']
 *                                         - Named function: 'my_json_encoder'
 *                                         - Static method string: 'MyClass::encode'
 *                                         - null to use native json_encode()
 * @param bool $strict If strict, not use json_encode but a standard loop.
 *
 * @return string The binary CBOR string.
 *
 * @see toAssociativeArray() for details.
 *
 * @package oihana\core\json
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function cbor_encode
(
    mixed                    $data ,
    string|array|object|null $helper  = null  ,
    bool                     $strict   = false ,
) :string
{
    if ( is_array( $data ) || is_object( $data ) )
    {
        $data = toAssociativeArray( $data , $helper , $strict ); ;
    }

    static $cborEncoder = null;

    if ( $cborEncoder === null )
    {
        $cborEncoder = new Encoder() ;
    }

    return $cborEncoder->encode( $data ) ;
}