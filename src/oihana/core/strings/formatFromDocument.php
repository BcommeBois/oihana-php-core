<?php

namespace oihana\core\strings ;

use function oihana\core\accessors\getKeyValue ;

/**
 * Format a template string using key-value pairs from an array or object.
 *
 * This function replaces placeholders in the template with corresponding values
 * found in the provided associative array or object. Placeholders are defined
 * by a prefix and suffix (default `{{` and `}}`), and keys can be nested using
 * the separator (default `.`).
 *
 * @param string       $template        The string to format.
 * @param array|object $document        Key-value pairs for placeholders.
 * @param string       $prefix          Placeholder prefix (default `{{`).
 * @param string       $suffix          Placeholder suffix (default `}}`).
 * @param string       $separator       Separator used to traverse nested keys (default `.`).
 * @param string|null  $pattern         Optional full regex pattern to match placeholders (including delimiters).
 * @param bool         $preserveMissing If true, preserves unresolved placeholders instead of removing them (default false).
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
 * ```
 *
 * Custom pattern example
 * ```
 * $template = 'Hello, <<name>>!';
 * $doc = ['name' => 'Alice'];
 * $pattern = '/<<(.+?)>>/';
 * echo formatFromDocument($template, $doc, '<<', '>>', '.', $pattern);
 * // Output: 'Hello, Alice!'
 * ```
 *
 * Preserve unresolved placeholder
 * ```php
 * echo formatFromDocument
 * (
 *     'Hello, {{name}}! From {{country}}.',
 *     ['name' => 'Alice'] ,
 *     preserveMissiong: true
 * ) ;
 * // Output: 'Hello, Alice! From {{country}}.'
 * ```
 */
function formatFromDocument
(
    string       $template                ,
    array|object $document                ,
    string       $prefix          = '{{'  ,
    string       $suffix          = '}}'  ,
    string       $separator       = '.'   ,
    ?string      $pattern         = null  ,
    bool         $preserveMissing = false ,
)
:string
{
    if( $pattern == null )
    {
        $escapedPrefix = preg_quote( $prefix , '/' ) ;
        $escapedSuffix = preg_quote( $suffix , '/' ) ;
        $pattern       = '/' . $escapedPrefix . '([a-zA-Z0-9_.\-\[\]]+)' . $escapedSuffix . '/';
    }

    $isExactSinglePattern = preg_match
    (
        pattern  : '/^' .
                   preg_quote($prefix, '/') .
                   '([a-zA-Z0-9_.\-\[\]]+)' .
                   preg_quote($suffix, '/') .
                   '$/',
        subject  : $template,
       matches :  $matches
    );

    if ( $isExactSinglePattern )
    {
        $key   = $matches[1] ;
        $value = getKeyValue( $document , $key , null , $separator ) ;
        if ( $value === null )
        {
            return $preserveMissing ? $prefix . $key . $suffix : '' ;
        }
        return $value ;
    }

    return preg_replace_callback( $pattern , function ( $matches ) use ( $document ,$preserveMissing , $prefix , $separator , $suffix ): string
    {
        $key = $matches[1];
        $value = getKeyValue( $document , $key , null , $separator ) ;

        if ( $value === null )
        {
            return $preserveMissing ? $prefix . $key . $suffix : '' ;
        }

        return (string) $value;
    }
    , $template ) ;
}