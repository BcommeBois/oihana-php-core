<?php

namespace oihana\core\cbor ;

use CBOR\ByteStringObject;
use CBOR\CBORObject;
use CBOR\ListObject;
use CBOR\MapObject;
use CBOR\OtherObject\DoublePrecisionFloatObject;
use CBOR\OtherObject\HalfPrecisionFloatObject;
use CBOR\OtherObject\SinglePrecisionFloatObject;
use CBOR\TextStringObject;

/**
 * Converts a CBORObject into a native PHP type with strict type fidelity.
 *
 * This function recursively traverses CBOR Maps and Lists to produce a normalized PHP structure.
 * It specifically addresses type coercion issues to maintain data integrity:
 *
 * - **String Fidelity:** CBOR Text Strings (e.g., "123") are strictly returned as PHP strings,
 * never cast to integers.
 * - **Integer Safety:** CBOR Integers are returned as PHP integers. If an integer exceeds
 * the system's limits (PHP_INT_MAX), it is preserved as a numeric string to prevent precision loss.
 * - **Floats:** CBOR Float objects are explicitly cast to PHP floats.
 *
 * @param CBORObject $object The raw CBOR object to convert.
 *
 * @return mixed The corresponding PHP native value (array, string, int, float, bool, or null).
 *
 * @package oihana\core\cbor
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function cborToPhp( CBORObject $object ): mixed
{
    if ( $object instanceof MapObject )
    {
        $data = [];
        foreach ( $object as $entry )
        {
            $key        = cborToPhp( $entry->getKey()   ) ;
            $value      = cborToPhp( $entry->getValue() ) ;
            $data[$key] = $value ;
        }
        return $data ;
    }

    if ( $object instanceof ListObject )
    {
        $data = [] ;
        foreach ( $object as $item )
        {
            $data[] = cborToPhp( $item ) ;
        }
        return $data;
    }

    if ( $object instanceof TextStringObject || $object instanceof ByteStringObject )
    {
        return $object->getValue() ;
    }

    if ($object instanceof SinglePrecisionFloatObject ||
        $object instanceof DoublePrecisionFloatObject ||
        $object instanceof HalfPrecisionFloatObject)
    {
        return (float) $object->normalize() ;
    }

    $value = $object->normalize();

    if ( !is_string($value ) )
    {
        return $value ;
    }

    if ( is_numeric( $value ) )
    {
        $isInteger = ctype_digit($value) || (str_starts_with($value, '-') && ctype_digit(substr($value, 1)));
        if ( $isInteger )
        {
            if ( $value > PHP_INT_MIN && $value < PHP_INT_MAX )
            {
                return (int) $value;
            }
        }
    }

    return $value;
}
