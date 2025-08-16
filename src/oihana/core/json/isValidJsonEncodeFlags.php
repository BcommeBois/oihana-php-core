<?php

namespace oihana\core\json ;

/**
 * Checks whether a given integer value is a valid combination of `json_encode()` option flags.
 *
 * The function compares the provided bitmask against the list of officially supported `JSON_*`
 * constants in the current PHP version. If any unsupported bits are set, the function will return `false`.
 *
 * > Note: PHP itself does not validate unknown flag bits in `json_encode()` — they are simply ignored.
 * > This helper ensures stricter validation.
 *
 * @param int $flags One or more `JSON_*` constants combined with bitwise OR (`|`).
 *
 * @return bool Returns `true` if the given flags are a valid combination of `json_encode()` options,
 *              `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\json\isValidJsonEncodeFlags;
 *
 * var_dump( isValidJsonEncodeFlags( 0 ) );                                       // true (no options)
 * var_dump( isValidJsonEncodeFlags( JSON_PRETTY_PRINT) );                       // true
 * var_dump( isValidJsonEncodeFlags( JSON_HEX_TAG | JSON_UNESCAPED_SLASHES ) ) ; // true
 * var_dump( isValidJsonEncodeFlags( JSON_PRETTY_PRINT | 123456 ) );             // false
 * ```
 *
 * @package oihana\core\json
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isValidJsonEncodeFlags( int $flags ): bool
{
    $validFlags = JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS
                | JSON_HEX_QUOT
                | JSON_FORCE_OBJECT
                | JSON_NUMERIC_CHECK
                | JSON_UNESCAPED_SLASHES
                | JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_PARTIAL_OUTPUT_ON_ERROR
                | JSON_PRESERVE_ZERO_FRACTION
                | JSON_UNESCAPED_LINE_TERMINATORS
                | JSON_INVALID_UTF8_IGNORE
                | JSON_INVALID_UTF8_SUBSTITUTE
                | JSON_THROW_ON_ERROR ;

    return ( $flags & ~$validFlags ) === 0 ;
}