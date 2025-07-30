<?php

namespace oihana\core\strings ;

/**
 * Format a template string using an external document.
 *
 * This function supports different types of documents:
 * - If the document is a string, it replaces all placeholders with that string.
 * - If the document is an array or object, it replaces placeholders by key lookup.
 *
 * @param string              $template  The string to format.
 * @param array|object|string $document  Key-value pairs for placeholders.
 * @param string              $prefix    Placeholder prefix (default `{{`).
 * @param string              $suffix    Placeholder suffix (default `}}`).
 * @param string              $separator Separator used to traverse nested keys (default `.`).
 * @param string|null         $pattern   Optional full regex pattern to match placeholders (including delimiters).
 *
 * @return string The formatted string.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 *
 * @example
 * ```php
 * use function oihana\core\strings\format;
 *
 * // Using an array document
 * echo format('Hello, {{name}}!', ['name' => 'Alice']);
 * // Output: 'Hello, Alice!'
 *
 * // Using nested keys with separator
 * echo format('ZIP: {{user.address.zip}}',
 * [
 *    'user' => ['address' => ['zip' => '75000']]
 * ]);
 * // Output: 'ZIP: 75000'
 *
 * // Using a custom prefix and suffix
 * echo format( 'Today is [[day]]' , ['day' => 'Monday'] , '[[' , ']]' ) ;
 * // Output: 'Today is Monday'
 *
 * // Using a full custom regex pattern
 * $template = 'Hello, <<name>>!' ;
 * $doc      = ['name' => 'Alice'] ;
 * $pattern  = '/<<(.+?)>>/' ;
 * echo format($template, $doc, '<<', '>>', '.', $pattern);
 * // Output: 'Hello, Alice!'
 *
 * // Using a string document: all placeholders replaced by same string
 * echo format( 'User: {{name}}, Role: {{role}}', 'anonymous');
 * // Ou
 */
function format
(
    string              $template  ,
    array|object|string $document  ,
    string              $prefix    = '{{' ,
    string              $suffix    = '}}' ,
    string              $separator = '.' ,
   ?string              $pattern   = null
)
:string
{
    if ( is_string( $document ) )
    {
        if ( $pattern === null )
        {
            $escapedPrefix = preg_quote( $prefix , '/' ) ;
            $escapedSuffix = preg_quote( $suffix , '/' ) ;
            $pattern       = '/' . $escapedPrefix . '([a-zA-Z0-9_\.\-\[\]]+)' . $escapedSuffix . '/' ;
        }
        return preg_replace( $pattern , $document , $template ) ;
    }

    return formatFromDocument($template, $document, $prefix, $suffix, $separator, $pattern);
}