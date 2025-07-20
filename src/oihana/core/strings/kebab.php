<?php

namespace oihana\core\strings ;

/**
 * Converts a camelCase or PascalCase string into kebab-case (lowercase with hyphens).
 *
 * Internally uses the `snake()` function with `-` as the separator.
 * Useful for URL slugs, CSS class names, or readable identifiers.
 *
 * If the input is `null` or empty, an empty string is returned.
 *
 * @param string|null $source The input string to transform.
 * @return string The kebab-case representation of the input.
 *
 * @example
 * ```php
 * echo kebab("helloWorld");      // Outputs: "hello-world"
 * echo kebab("HelloWorld");      // Outputs: "hello-world"
 * echo kebab("XMLHttpRequest");  // Outputs: "xml-http-request"
 * echo kebab(null);              // Outputs: ""
 * ```
 */
function kebab( ?string $source): string
{
    return snake( $source , '-' ) ;
}