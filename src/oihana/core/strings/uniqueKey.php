<?php

namespace oihana\core\strings ;

use Closure;
use Normalizer;

/**
 * Generates a unique, deterministic key from context and parameters.
 *
 * This function produces a stable key suitable for caching or indexing.
 * The key is guaranteed to be identical for the same input values regardless of
 * parameter order. Values are serialized consistently, and the final key is
 * optionally hashed using SHA-256 to produce a fixed-length string.
 *
 * Key generation process:
 * 1. Start with the prefix (e.g., cache namespace)
 * 2. Add the context identifier if provided
 * 3. Sort bind parameters alphabetically by key
 * 4. Serialize each bind value according to its type:
 * - null      → 'null'
 * - boolean   → 'true' or 'false'
 * - array     → JSON encoded
 * - object    → serialized, unless a Closure → 'closure'
 * - other     → cast to string
 * 5. Join all parts with the separator
 * 6. Normalize the resulting string using Normalizer::FORM_C
 * 7. Optionally hash the string with SHA-256
 *
 * Type handling for bind values:
 * - null     → 'null'
 * - boolean  → 'true' or 'false'
 * - array    → JSON encoded
 * - object   → JSON encoded
 * - other    → string cast
 *
 * @param string|null $context Context identifier (e.g., operation or query name)
 *                             used to differentiate cache entries.
 * @param array|null $binds    Associative array of parameters to include in the key.
 *                             Keys are sorted alphabetically to ensure consistency.
 *                             Values can be scalars, arrays, objects, or closures.
 *                             Example: ['provider' => 'P123', 'date' => '2025-01-15']
 * @param string $prefix       Optional prefix or namespace for the key (default: '').
 * @param string $separator   Character used to join key parts (default: ':').
 * @param bool   $hash        Whether to hash the final key using SHA-256 (default: true).
 *                            If false, returns the normalized, joined string.
 *
 * @return string SHA-256 hash (64 hexadecimal characters) or normalized key string if $hash is false.
 *
 * @example
 *
 * 1 - Basic usage
 *```php
 * $key = uniqueKey
 * (
 *     context : 'product_price',
 *     binds   : ['product_id' => 123, 'warehouse' => 'W01'],
 *     prefix  : 'prices'
 * );
 * // Returns: "a1b2c3d4..." (SHA-256 hash)
 * ```
 *
 * 2 - Same parameters in different order produce identical keys
 * ```php
 * $key1 = uniqueKey(context: 'test', binds: ['a' => 1, 'b' => 2]);
 * $key2 = uniqueKey(context: 'test', binds: ['b' => 2, 'a' => 1]);
 * // $key1 === $key2 (true)
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function uniqueKey
(
    ?string $context  ,
     ?array $binds     = null ,
     string $prefix    = '' ,
     string $separator = ':' ,
       bool $hash      = true
)
: string
{
    $keyParts = [ $prefix ] ;

    if ( $context )
    {
        $keyParts[] = 'ctx=' . $context ;
    }

    if ( is_array( $binds ) && !empty( $binds ) )
    {
        ksort($binds ) ;

        foreach ( $binds as $k => $v )
        {
            $serializedValue = match( true )
            {
                is_null   ( $v ) => 'null',
                is_bool   ( $v ) => $v ? 'true' : 'false',
                is_array  ( $v ) => json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ,
                is_object ( $v ) => $v instanceof Closure ? 'closure' : serialize( $v ) ,
                default   => ( string ) $v
            };

            $keyParts[] = $k . '=' . $serializedValue ;
        }
    }

    $fullKey = implode( $separator , $keyParts ) ;

    $fullKey = Normalizer::normalize($fullKey, Normalizer::FORM_C) ?: $fullKey;

    return $hash ? hash('sha256' , $fullKey ) : $fullKey ;
}