<?php

namespace oihana\core\strings ;

/**
 * Returns a transformed key by optionally prepending a prefix.
 *
 * This function allows you to prefix a key string with another string,
 * separated by a custom separator. If the prefix is empty or null,
 * the original key is returned unchanged.
 *
 * @param null|string|array $key The key to transform :
 *                               - `null` returns an empty string
 *                               - `string` is used directly
 *                               - `array` is joined using the `$separator`
 * @param string|null $prefix Optional prefix to prepend. Default is an empty string.
 *                            If `null` or empty, the prefix is ignored.
 *
 * @param string $separator The separator used both to join array keys and to separate the prefix from the key.
 *
 * @return string The transformed key.
 *          Returns an empty string if `$key` is `null`.
 *          Returns `"{$prefix}{$separator}{$key}"` when a prefix is provided,
 *          otherwise returns the normalized key.
 *
 * @example
 * ```php
 * use function oihana\core\strings\key;
 *
 * key('name');                  // 'name'
 * key('name', 'doc');           // 'doc.name'
 * key('name', 'doc', '::');     // 'doc::name'
 * key('name', 'doc', '->');     // 'doc->name'
 * key('name', '');              // 'name'
 * key('name', null);            // 'name'
 *
 * key(['a', 'b'], 'doc');       // 'doc.a.b'
 * key(['a', 'b'], 'doc', '::'); // 'doc::a::b'
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function key( null|string|array $key , ?string $prefix = '' , string $separator = '.' ) :string
{
    if ( $key === null )
    {
        return '' ;
    }

    if ( is_array( $key ) )
    {
        $key = implode( $separator , $key ) ;
    }

    if ( $key === '' )
    {
        return ''  ;
    }

    if( $prefix === null )
    {
        $prefix = '' ;
    }

    return $prefix ? $prefix . $separator . $key : $key ;
}