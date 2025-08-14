<?php

namespace oihana\core\strings ;

/**
 * Converts a camelCase or PascalCase string into a format where the first word
 * is separated by a dot (.) from the rest, which are joined using kebab-case (-).
 *
 * This is useful for creating structured identifiers or keys that use a
 * namespace-like prefix separated by a dot, followed by a kebab-case suffix.
 *
 * Internally, this function uses {@see snake()} to normalize the input string
 * into lowercase segments separated by a custom delimiter.
 *
 * Examples:
 * - "serverAskJwtSecret" → "server.ask-jwt-secret"
 * - "UserProfilePage"    → "user.profile-page"
 * - "APIKeyManager"      → "api.key-manager"
 * - "foo"                → "foo"
 * - null                 → ""
 *
 * @param string|null $source The camelCase or PascalCase string to convert.
 * @return string The converted string in dot + kebab-case format.
 *
 * @example
 * ```php
 * echo dotKebab("serverAskJwtSecret");
 * // Output: "server.ask-jwt-secret"
 *
 * echo dotKebab("UserProfilePage");
 * // Output: "user.profile-page"
 *
 * echo dotKebab("APIKeyManager");
 * // Output: "api.key-manager"
 *
 * echo dotKebab("foo");
 * // Output: "foo"
 *
 * echo dotKebab(null);
 * // Output: ""
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function dotKebab( ?string $source ): string
{
    if ( !is_string( $source ) || $source === '' )
    {
        return '';
    }

    $kebab = snake( $source , '-' ) ;
    $parts = explode('-' , $kebab ) ;

    if ( count( $parts ) <= 1 )
    {
        return $kebab ;
    }

    $first = array_shift($parts ) ;

    return $first . '.' . implode('-' , $parts ) ;
}