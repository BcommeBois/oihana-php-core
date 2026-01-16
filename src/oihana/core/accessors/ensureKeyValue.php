<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use Closure;
use Error;
use InvalidArgumentException;

/**
 * Ensures that one or more keys or properties exist in an array or object.
 *
 * Missing keys are created and initialized. Defaults can be defined globally or per key.
 * Nested keys are supported using a separator (default: '.'). Intermediate path segments
 * are created automatically if missing.
 *
 * Features:
 * - **Specific Defaults:** Pass an associative array to `$keys` to set different defaults per key.
 * - **Lazy Loading:** Pass a `Closure` as a default value to calculate it only if needed.
 * - **Typed Properties:** With `$enforce = true`, strictly checks initialized state of typed properties.
 *
 * @param array|object $document  The target document (array or object).
 * @param string|array $keys      A single key, a list of keys `['a', 'b']`, or an associative map `['key' => 'default']`.
 * @param mixed        $default   Global default value (or Closure) for keys without a specific default. Default is `null`.
 * @param string       $separator Separator used to split nested keys. Default is '.'.
 * @param bool|null    $isArray   Optional: true for array mode, false for object mode, null to auto-detect.
 * @param bool         $enforce   Force initialization of non-initialized typed properties. Default: false.
 *
 * @return array|object The updated document with ensured keys.
 *
 * @throws InvalidArgumentException If the document or key definition is invalid.
 *
 * @example
 * Ensure simple keys with a global default :
 * ```php
 * $doc = [];
 * $doc = ensureKeyValue($doc, ['name', 'age'], null);
 * // Result: ['name' => null, 'age' => null]
 * ```
 *
 * Ensure keys with specific default values :
 * ```php
 * $doc = [];
 * $doc = ensureKeyValue($doc, [
 * 'role' => 'guest',   // Specific default
 * 'active'             // Uses global default (true)
 * ], true);
 * // Result: ['role' => 'guest', 'active' => true]
 * ```
 *
 * Use a Closure for lazy evaluation (heavy calculation) :
 * ```php
 * $doc = [];
 * $doc = ensureKeyValue($doc, 'created_at', fn() => new DateTimeImmutable());
 * // Result: ['created_at' => object(DateTimeImmutable)]
 * ```
 *
 * Enforce initialization of typed properties (PHP 7.4+) :
 * ```php
 * class User { public string $name; } // Uninitialized
 * $doc = new User();
 *
 * // Without enforce: Property exists, so it's skipped (remains uninitialized).
 * // With enforce: Detects uninitialized state and sets default.
 * $doc = ensureKeyValue($doc, 'name', 'Anonymous', '.', null, true);
 *
 * // Result: User object with $name = 'Anonymous'
 * ```
 *
 * Use with named arguments (PHP 8+) to skip optional parameters:
 * ```php
 * $doc = new User();
 * // We skip separator and isArray to target 'enforce' directly
 * ensureKeyValue($doc, 'name', default: 'John', enforce: true);
 * ```
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function ensureKeyValue
(
    array|object $document          ,
    string|array $keys              ,
    mixed        $default   = null  ,
    string       $separator = '.'   ,
    ?bool        $isArray   = null  ,
    bool         $enforce   = false
)
:array|object
{
    $keysToProcess = is_string( $keys ) ? [ $keys ] : $keys ;

    foreach ( $keysToProcess as $keyOrIndex => $valueOrKey )
    {
        if ( is_string( $keyOrIndex ) )
        {
            $key           = $keyOrIndex ;
            $targetDefault = $valueOrKey ;
        }
        else
        {
            $key           = $valueOrKey ;
            $targetDefault = $default ;
        }

        $isArray = assertDocumentKeyValid( $document , $key , $separator , $isArray ) ;
        $exists  = hasKeyValue( $document , $key , $separator , $isArray ) ;

        if ( $exists )
        {
            if ( $isArray || !$enforce )
            {
                continue ;
            }

            try
            {
                if ( str_contains( $key , $separator ) )
                {
                    $path    = explode( $separator , $key );
                    $lastKey = array_pop( $path ) ;
                    $target  = $document ;

                    foreach ( $path as $segment )
                    {
                        $target = is_array( $target ) ? $target[ $segment ] : $target->{ $segment } ;
                    }

                    $void = is_array( $target ) ? $target[ $lastKey ] : $target->{ $lastKey } ;
                }
                else
                {
                    $void = $document->{ $key } ;
                }
            }
            catch ( Error )
            {
                $finalValue = $targetDefault instanceof Closure ? $targetDefault() : $targetDefault ;
                $document   = setKeyValue( $document , $key , $finalValue , $separator , false ) ;
            }

            continue ;
        }

        $finalValue = $targetDefault instanceof Closure ? $targetDefault() : $targetDefault ;
        $document   = setKeyValue( $document , $key , $finalValue , $separator , $isArray ) ;
    }

    return $document ;
}