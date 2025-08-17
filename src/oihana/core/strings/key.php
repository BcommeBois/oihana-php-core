<?php

namespace oihana\core\strings ;

/**
 * Returns a transformed key by optionally prepending a prefix.
 *
 * This function allows you to prefix a key string with another string,
 * separated by a custom separator. If the prefix is empty or null,
 * the original key is returned unchanged.
 *
 * @param string      $key       The key to transform.
 * @param string|null $prefix    Optional prefix to prepend. Default is an empty string.
 * @param string      $separator The separator to use between the prefix and key. Default is '.'.
 *
 * @return string The transformed key. If prefix is provided, returns "prefix{separator}key",
 *                otherwise returns the original key.
 *
 * @example
 * ```php
 * use function oihana\core\strings\key;
 *
 * key('name');                  // Returns 'name'
 * key('name', 'doc');           // Returns 'doc.name'
 * key('name', 'doc', '::');     // Returns 'doc::name'
 * key('name', 'doc', '->');     // Returns 'doc->name'
 * key('name', '');              // Returns 'name'
 * key('name', null);            // Returns 'name'
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function key( string $key , ?string $prefix = '' , string $separator = '.' ) :string
{
    if( $prefix === null )
    {
        $prefix = '' ;
    }
    return $prefix ? $prefix . $separator . $key : $key;
}