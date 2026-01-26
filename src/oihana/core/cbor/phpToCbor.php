<?php

namespace oihana\core\cbor ;

use CBOR\ByteStringObject;
use CBOR\CBORObject;
use CBOR\ListObject;
use CBOR\MapObject;
use CBOR\NegativeIntegerObject;
use CBOR\OtherObject\DoublePrecisionFloatObject;
use CBOR\OtherObject\FalseObject;
use CBOR\OtherObject\HalfPrecisionFloatObject;
use CBOR\OtherObject\NullObject;
use CBOR\OtherObject\SinglePrecisionFloatObject;
use CBOR\OtherObject\TrueObject;
use CBOR\TextStringObject;
use CBOR\UnsignedIntegerObject;
use InvalidArgumentException;
use function oihana\core\objects\toAssociativeArray;

/**
 * Converts any PHP data into a CBORObject instance.
 *
 * This function maps native PHP types to their corresponding CBOR Object representations:
 * - Arrays (associative) -> MapObject
 * - Arrays (indexed)     -> ListObject
 * - Integers             -> UnsignedIntegerObject or NegativeIntegerObject
 * - Floats               -> DoublePrecisionFloatObject
 * - Strings              -> TextStringObject
 * - Booleans             -> TrueObject or FalseObject
 * - Null                 -> NullObject
 *
 * Objects are automatically converted to associative arrays using toAssociativeArray().
 *
 * @param mixed                    $data   The PHP data to convert.
 * @param string|array|object|null $helper Optional internal encoder helper used by toAssociativeArray.
 *
 * @return CBORObject The constructed CBOR object tree.
 *
 * @throws InvalidArgumentException If the data type is not supported.
 *
 * @package oihana\core\cbor
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function phpToCbor( mixed $data , string|array|object|null $helper = null ) :CBORObject
{
    // 1. Object normalization (uses your core helper)
    if ( is_object( $data ) )
    {
        $data = toAssociativeArray( $data , $helper ) ;
    }

    // 2. Maps (Associative Arrays)
    if  ( is_array( $data ) && !array_is_list( $data ) )
    {
        $map = MapObject::create() ;
        foreach ( $data as $key => $value )
        {
            $keyObj = phpToCbor( $key   , $helper ) ;
            $valObj = phpToCbor( $value , $helper ) ;
            $map->add( $keyObj , $valObj ) ;
        }
        return $map;
    }

    // 3. Lists (Indexed Arrays)
    if ( is_array( $data ) )
    {
        $list = ListObject::create();
        foreach ( $data as $value )
        {
            $list->add( phpToCbor( $value , $helper ) ) ;
        }
        return $list;
    }

    // 4. Integers (Positive & Negative)
    if ( is_int( $data ) )
    {
        return $data >= 0
            ? UnsignedIntegerObject::create( $data )
            : NegativeIntegerObject::create( $data ) ;
    }

    // 5. Floats (Default to Double Precision for PHP floats)
    if( is_float( $data ) )
    {
        return DoublePrecisionFloatObject::create( $data ) ;
    }

    // 6. Strings (Default to TextString)
    // Note: If you need ByteString, you should manually instantiate ByteStringObject
    // before passing it to this function, or detect binary content.
    if ( is_string( $data ) )
    {
        return TextStringObject::create( $data ) ;
    }

    // 7. Booleans
    if ( is_bool( $data ) )
    {
        return $data ? TrueObject::create() : FalseObject::create() ;
    }

    // 8. Null
    if ( $data === null )
    {
        return NullObject::create() ;
    }

    // 9. Fallback (Resources or unknown types)
    throw new InvalidArgumentException( sprintf
    (
        'Unsupported type for CBOR conversion: %s' ,
        get_debug_type( $data )
    ));
}
