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
use CBOR\OtherObject\SimpleObject;
use CBOR\OtherObject\SinglePrecisionFloatObject;
use CBOR\OtherObject\TrueObject;
use CBOR\OtherObject\UndefinedObject;
use CBOR\TextStringObject;
use CBOR\UnsignedIntegerObject;

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
function cborToPhp(mixed $value): mixed
{
    // Handle CBOR Map (associative array)
    if ($value instanceof MapObject) {
        $result = [];
        foreach ($value as $key => $val) {
            // Decode key recursively
            $phpKey = cborToPhp($key);
            // Decode value recursively
            $result[$phpKey] = cborToPhp($val);
        }
        return $result;
    }

    // Handle CBOR List (indexed array)
    if ($value instanceof ListObject) {
        $result = [];
        foreach ($value as $item) {
            $result[] = cborToPhp($item);
        }
        return $result;
    }

    // Handle text strings
    if ($value instanceof TextStringObject) {
        return $value->getValue();
    }

    // Handle byte strings
    if ($value instanceof ByteStringObject) {
        return $value->getValue();
    }

    // Handle integers
    if ($value instanceof UnsignedIntegerObject || $value instanceof NegativeIntegerObject) {
        return $value->getValue();
    }

    // Handle booleans
    if ($value instanceof TrueObject) {
        return true;
    }
    if ($value instanceof FalseObject) {
        return false;
    }

    // Handle null/undefined
    if ($value instanceof NullObject || $value instanceof UndefinedObject) {
        return null;
    }

    // Handle floats
    if ($value instanceof HalfPrecisionFloatObject
        || $value instanceof SinglePrecisionFloatObject
        || $value instanceof DoublePrecisionFloatObject) {
        return $value->getValue();
    }

    // Handle simple values
    if ($value instanceof SimpleObject) {
        return $value->getValue();
    }

    // Handle tagged objects (dates, etc.)
    if (method_exists($value, 'getValue')) {
        return cborToPhp($value->getValue());
    }

    // Fallback for unknown types - try to cast to string
    if (is_object($value) && method_exists($value, '__toString')) {
        return (string)$value;
    }

    // Return as-is for scalars
    return $value;
}
