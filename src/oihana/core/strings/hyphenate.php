<?php

namespace oihana\core\strings ;

/**
 * Converts a camelCase or PascalCase string to a hyphenated (kebab-case) string.
 *
 * This is useful for transforming class names or identifiers into URL- or CSS-friendly format.
 *
 * Examples:
 * - "helloWorld" → "hello-world"
 * - "HTMLParser" → "html-parser"
 * - "aTestString" → "a-test-string"
 *
 * @param string|null $source The camelCase or PascalCase string to convert.
 * @return string The hyphenated (kebab-case) string.
 *
 * @example
 * ```php
 * echo hyphenate("helloWorld");
 * // Output: "hello-world"
 *
 * echo hyphenate("SimpleXMLParser");
 * // Output: "simple-xml-parser"
 *
 * echo hyphenate("AString");
 * // Output: "a-string"
 *
 * echo hyphenate(null);
 * // Output: ""
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function hyphenate( ?string $source ): string
{
    return snake( $source , '-' ) ;
}