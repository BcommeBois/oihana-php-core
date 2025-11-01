<?php

namespace oihana\core\strings ;

/**
 * Replaces placeholders in a path string with values from an associative array.
 *
 * Placeholders in the path are expected in one of the following forms:
 * - `{name}` : simple placeholder.
 * - `{name:regex}` : placeholder with an optional regex pattern.
 *
 * If a value for a placeholder is not provided in `$args`, the placeholder
 * remains unchanged in the output.
 *
 * ### Examples
 *
 * Simple replacement:
 * ```php
 * $path = '/observation/{observation}/workspace/{workspace}/places';
 * $args = ['observation' => '15454', 'workspace' => '787878'];
 * echo replacePathPlaceholders($path, $args);
 * // Output: '/observation/15454/workspace/787878/places'
 * ```
 *
 * Undefined placeholder fallback:
 * ```php
 * $path = '/foo/{missing}/bar';
 * $args = ['other' => 'value'];
 * echo replacePathPlaceholders($path, $args);
 * // Output: '/foo/{missing}/bar'  (placeholder stays unchanged)
 * ```
 *
 * Placeholder with regex:
 * ```php
 * $path = '/product/{product:[A-Za-z0-9_]+}/warehouse/{warehouse:[0-9]+}';
 * $args = ['product' => 'ABC123', 'warehouse' => '42'];
 * echo replacePathPlaceholders($path, $args);
 * // Output: '/product/ABC123/warehouse/42'
 * ```
 *
 * Custom regex pattern:
 * ```php
 * $path = '/foo/:bar/baz';
 * $args = ['bar' => '123'];
 * $pattern = '/:([a-zA-Z0-9_]+)/';
 * echo replacePathPlaceholders($path, $args, $pattern);
 * // Output: '/foo/123/baz'
 * ```
 *
 * Empty path or null:
 * ```php
 * echo replacePathPlaceholders(null); // Output: ''
 * echo replacePathPlaceholders('');   // Output: ''
 * ```
 *
 * @param string|null $path The path containing placeholders, e.g., '/users/{id}'.
 * @param array $args Associative array of placeholder values (key = placeholder name).
 * @param string $pattern Regex pattern to detect placeholders (default: '/\{([a-zA-Z0-9_]+)(:[^}]+)?}/').
 *
 * @return string The path with placeholders replaced by their corresponding values.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function replacePathPlaceholders( ?string $path , array $args = [] , string $pattern = '/\{([a-zA-Z0-9_]+)(:[^}]+)?}/' ): string
{
    if( $path == null || $path == '' )
    {
        return '' ;
    }

    if ( empty( $args ) )
    {
        return $path ;
    }

    return preg_replace_callback
    (
        $pattern ,
        fn ( $matches ) => $args[ $matches[1] ] ?? $matches[0] , // fallback if undefined
        $path
    );
}