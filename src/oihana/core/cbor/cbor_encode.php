<?php

namespace oihana\core\cbor ;

use Beau\CborPHP\CborEncoder;
use Beau\CborPHP\exceptions\CborException;
use CBOR\Encoder;
use function oihana\core\objects\toAssociativeArray;

/**
 * Encodes any data into a CBOR binary string.
 * Automatically converts objects and arrays to pure associative arrays.
 *
 * @param mixed $data The data to encode (scalar, array, or object).
 *
 * @return string The binary CBOR string.
 *
 * @throws CborException
 *
 * @see toAssociativeArray() for details.
 *
 * @package oihana\core\json
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function cbor_encode
(
    mixed $data ,
)
:string
{
    if ( is_array( $data ) || is_object( $data ) )
    {
        $data = toAssociativeArray( $data , strict:true ) ;
    }

    static $cborEncoder = null;

    if ( $cborEncoder === null )
    {
        $cborEncoder = new Encoder() ;
    }

    return $cborEncoder->encode( $data ) ;

    // return CborEncoder::encode( $data ) ;
}