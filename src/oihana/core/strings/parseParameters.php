<?php

declare(strict_types=1);

namespace oihana\core\strings ;

/**
 * Parses a parameter string of the form `key=value` pairs joined by an
 * item separator, into an associative array.
 *
 * Each segment is split by the first occurrence of `$kvSep`. Segments
 * with no `$kvSep` are treated as bare tokens whose value is `''`.
 * Quoted values are unquoted with [[unquote]] (no escape decoding).
 *
 * Splitting respects quoted regions via [[splitOutsideQuotes]], so a
 * separator appearing inside `"…"` (or any recognised quote pair) does
 * not break parsing.
 *
 * Generic by design — no normalisation, no header-specific rules. For
 * RFC 7230 header parameters, wrap this with `$lowercaseKeys = true`.
 *
 * @param string $input         The string to parse.
 * @param string $itemSep       Separator between pairs (default `;`).
 * @param string $kvSep         Separator between key and value (default `=`).
 * @param bool   $lowercaseKeys Whether to lowercase keys (default `false`).
 *
 * @return array<string,string> The parsed parameters.
 *
 * @example
 * ```php
 * parseParameters('a=1; b=2');
 * // ['a' => '1', 'b' => '2']
 *
 * parseParameters('charset="utf-8"; boundary=xyz');
 * // ['charset' => 'utf-8', 'boundary' => 'xyz']
 *
 * parseParameters('Charset=UTF-8; Boundary=xyz', ';', '=', true);
 * // ['charset' => 'UTF-8', 'boundary' => 'xyz']
 *
 * parseParameters('flag; key=value');
 * // ['flag' => '', 'key' => 'value']
 *
 * parseParameters('key="a;b"; other=c');
 * // ['key' => 'a;b', 'other' => 'c']
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.8
 *
 * @see splitOutsideQuotes()
 * @see unquote()
 */
function parseParameters
(
    string $input         ,
    string $itemSep       = ';' ,
    string $kvSep         = '=' ,
    bool   $lowercaseKeys = false
)
:array
{
    $params = [] ;

    foreach ( splitOutsideQuotes( $input , $itemSep , true ) as $segment )
    {
        if ( $segment === '' )
        {
            continue ;
        }

        $eq = $kvSep === '' ? false : strpos( $segment , $kvSep ) ;

        if ( $eq === false )
        {
            $name = $lowercaseKeys ? strtolower( $segment ) : $segment ;
            $params[ $name ] = '' ;
            continue ;
        }

        $name  = trim( substr( $segment , 0 , $eq ) ) ;
        $value = trim( substr( $segment , $eq + strlen( $kvSep ) ) ) ;

        if ( $name === '' )
        {
            continue ;
        }

        if ( $lowercaseKeys )
        {
            $name = strtolower( $name ) ;
        }

        $params[ $name ] = unquote( $value ) ;
    }

    return $params ;
}
