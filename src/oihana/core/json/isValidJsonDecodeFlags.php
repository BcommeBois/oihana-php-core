<?php

namespace oihana\core\json ;

/**
 * Checks whether a given integer value is a valid combination of `json_decode()` option flags.
 *
 * The function compares the provided bitmask against the list of officially supported `JSON_*`
 * constants for `json_decode()` in the current PHP version.
 *
 * > Note: PHP itself does not validate unknown flag bits in `json_decode()` — they are simply ignored.
 * > This helper ensures stricter validation.
 *
 * @param int $flags One or more `JSON_*` constants combined with bitwise OR (`|`).
 *
 * @return bool Returns `true` if the given flags are a valid combination of `json_decode()` options,
 *              `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\json\isValidJsonDecodeFlags;
 *
 * var_dump( isValidJsonDecodeFlags(0) );                        // true
 * var_dump( isValidJsonDecodeFlags(JSON_BIGINT_AS_STRING) );    // true
 * var_dump( isValidJsonDecodeFlags(JSON_OBJECT_AS_ARRAY) );     // true
 * var_dump( isValidJsonDecodeFlags(JSON_INVALID_UTF8_IGNORE) ); // true
 * var_dump( isValidJsonDecodeFlags(1 << 30) );                  // false
 * ```
 *
 * @package oihana\core\json
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isValidJsonDecodeFlags( int $flags ): bool
{
    $validFlags = JSON_BIGINT_AS_STRING
                | JSON_INVALID_UTF8_IGNORE
                | JSON_INVALID_UTF8_SUBSTITUTE
                | JSON_OBJECT_AS_ARRAY
                | JSON_THROW_ON_ERROR ;

    return ( $flags & ~$validFlags ) === 0 ;
}