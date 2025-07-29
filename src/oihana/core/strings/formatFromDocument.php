<?php

namespace oihana\core\strings ;

use function oihana\core\accessors\getKeyValue ;

/**
 * Format a template string using external values.
 *
 * This function replaces placeholders in a given template string
 * with corresponding values from an associative array or object.
 * Placeholders are defined by a prefix and suffix (by default `{{` and `}}`)
 * surrounding a key name.
 *
 * The pattern used to detect placeholders can be customized by passing
 * a regular expression pattern matching the full placeholder including delimiters.
 * If omitted, a default pattern is constructed from prefix and suffix,
 * allowing keys with letters, digits, underscore, dot, brackets, and dash.
 *
 * If a key is not found in the data, the placeholder is replaced with an empty string.
 *
 * @param string $template The string to format.
 * @param array|object $document Key-value pairs for placeholders.
 * @param string $prefix Placeholder prefix (default `{{`).
 * @param string $suffix Placeholder suffix (default `}}`).
 * @param string $separator Separator used to traverse nested keys (default `.`).
 * @param string|null $pattern Optional full regex pattern to match placeholders (including delimiters).
 *
 * @return string The formatted string.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 *
 * @example
 * ```php
 * use function oihana\core\strings\formatFromDocument;
 *
 * echo formatFromDocument('Hello, {{name}}!', ['name' => 'Alice']);
 * // Output: 'Hello, Alice!'
 *
 * echo formatFromDocument('Today is [[day]]', ['day' => 'Monday'], '[[', ']]');
 * // Output: 'Today is Monday'
 *
 * echo formatFromDocument('ZIP: {{user.address.zip}}', [
 *     'user' => ['address' => ['zip' => '75000']]
 * ]);
 * // Output: 'ZIP: 75000'
 *
 * // Custom pattern example
 * $template = 'Hello, <<name>>!';
 * $doc = ['name' => 'Alice'];
 * $pattern = '/<<(.+?)>>/';
 * echo formatFromDocument($template, $doc, '<<', '>>', '.', $pattern);
 * // Output: 'Hello, Alice!'
 * ```
 */
function formatFromDocument
(
    string       $template  ,
    array|object $document  ,
    string       $prefix    = '{{' ,
    string       $suffix    = '}}' ,
    string       $separator = '.' ,
   ?string       $pattern   = null
)
:string
{
    if( $pattern == null )
    {
        $escapedPrefix = preg_quote( $prefix , '/' ) ;
        $escapedSuffix = preg_quote( $suffix , '/' ) ;
        $pattern       = '/' . $escapedPrefix . '([a-zA-Z0-9_\.\-\[\]]+)' . $escapedSuffix . '/';
    }

    return preg_replace_callback($pattern, function ($matches) use ( $document , $separator ): string
    {
        $key = $matches[1];
        return (string) getKeyValue( $document , $key , '' , $separator ) ;
    }
    , $template ) ;
}