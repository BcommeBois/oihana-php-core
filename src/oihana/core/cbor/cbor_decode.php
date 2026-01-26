<?php

namespace oihana\core\cbor ;

use CBOR\Decoder;
use CBOR\StringStream;
use InvalidArgumentException;

use RuntimeException;

/**
 * Decodes a CBOR binary string into a PHP normalized structure (array or scalar).
 *
 * @param string $data The binary CBOR data.
 *
 * @return mixed The decoded PHP data (array, int, string, etc.).
 *
 * @throws RuntimeException If decoding fails.
 *
 * @package oihana\core\json
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function cbor_decode( mixed $data ) :mixed
{
    try
    {
        static $cborDecoder = null ;

        if( $cborDecoder === null )
        {
            $cborDecoder = new Decoder() ;
        }

        $object = $cborDecoder->decode( StringStream::create( $data ) ) ;

        return cborToPhp( $object )  ;
    }
    catch ( InvalidArgumentException $e )
    {
        throw new RuntimeException('CBOR Decoding failed: ' . $e->getMessage() , 0 , $e ) ;
    }
}