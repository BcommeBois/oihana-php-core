<?php

namespace oihana\core\strings ;

/**
 * Converts a string into a URL-friendly slug.
 *
 * The transformation transliterates accented Latin characters to ASCII (via
 * {@see latinize()}), lower-cases the result, replaces every run of characters
 * that are not `a-z` or `0-9` with `$separator`, and trims leading/trailing
 * separators.
 *
 * @param string $source    The input string.
 * @param string $separator The separator inserted between words. Default `-`.
 *
 * @return string The slugified string (lower-case ASCII, words joined by `$separator`).
 *
 * @example
 * ```php
 * use function oihana\core\strings\slugify;
 *
 * echo slugify( 'Héllo, World!' )            ; // "hello-world"
 * echo slugify( '  Café Münsterländer  ' )   ; // "cafe-munsterlander"
 * echo slugify( 'Foo_Bar Baz' , '_' )        ; // "foo_bar_baz"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function slugify( string $source , string $separator = '-' ) :string
{
    $slug = latinize( $source ) ;
    $slug = mb_strtolower( $slug , 'UTF-8' ) ;
    $slug = preg_replace( '/[^a-z0-9]+/u' , $separator , $slug ) ;
    return trim( $slug , $separator ) ;
}
