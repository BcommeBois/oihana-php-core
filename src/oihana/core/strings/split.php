<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Splits a string into an array using a regular expression separator.
 *
 * This function is a wrapper around {@see preg_split()} with a simpler,
 * more expressive API and optional UTF-8 support.
 *
 * When `$utf8` is enabled, the separator is evaluated using the `/u` modifier,
 * allowing correct handling of multibyte characters.
 *
 * By default, the separator is treated as a **literal string**.
 * If you want to use a raw regular expression, pass it already delimited.
 *
 * ### Examples
 *
 * Basic usage:
 * ```php
 * split('foo,bar,baz', ',');      // ['foo', 'bar', 'baz']
 * split('a|b|c', '|');            // ['a', 'b', 'c']
 * split('one two  three', '\s+'); // ['one', 'two', 'three']
 * ```
 *
 * With limit:
 * ```php
 * split('a,b,c,d', ',', 2); // ['a', 'b,c,d']
 * ```
 *
 * Ignore case:
 * ```php
 * split('Foo|BAR|baz', 'bar', null, true); // ['Foo|', '|baz']
 * ```
 *
 * UTF-8:
 * ```php
 * split('éà üö', '\s+', null, false, null, true); // ['éà', 'üö']
 * ```
 *
 * Null or empty source:
 * ```php
 * split(null, ','); // []
 * split('', ',');   // []
 * ```
 *
 * @param string|null $source
 * The source string to split. If `null`, it is treated as an empty string.
 *
 * @param string $separator
 * The separator pattern or literal string.
 *
 * @param int|null $limit
 * If specified, limits the number of returned elements.
 *
 * @param bool $ignoreCase
 * Whether the separator matching should be case-insensitive.
 *
 * @param int|null $flags
 * Optional `PREG_SPLIT_*` flags.
 *
 * @param bool $utf8
 * Whether to enable UTF-8 (`/u`) mode.
 *
 * @return array
 * An array of split segments.
 *
 * @throws InvalidArgumentException
 * If the separator is empty.
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
function split
(
    ?string $source     ,
    string  $separator  ,
    ?int    $limit      = null ,
    bool    $ignoreCase = false ,
    ?int    $flags      = null ,
    bool    $utf8       = false
)
: array
{
    $source ??= '' ;

    if ( $source === '' )
    {
        return [] ;
    }

    if ( $separator === '' )
    {
        throw new InvalidArgumentException('Separator must not be empty.') ;
    }

    // Detect if the separator is already a regex (delimited)
    $isRegex = strlen( $separator ) > 2 && $separator[0] === '/' && strrpos( $separator , '/' ) !== 0 ;

    if ( !$isRegex )
    {
        $separator = preg_quote( $separator , '/' ) ;
    }

    $pattern = $isRegex ? $separator : '/' . $separator . '/' ;

    // Ajouter les modificateurs
    if ( $ignoreCase )
    {
        $pattern .= 'i' ;
    }

    if ( $utf8 )
    {
        $pattern .= 'u' ;
    }

    return preg_split( $pattern, $source, $limit ?? -1, $flags ?? 0 ) ;
}
