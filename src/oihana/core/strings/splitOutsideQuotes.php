<?php

declare(strict_types=1);

namespace oihana\core\strings ;

/**
 * Splits a string by a separator, ignoring separators that appear inside a quoted region.
 *
 * Recognised quote pairs:
 * - `'…'`  single quotes
 * - `"…"`  double quotes
 * - `` `…` `` backticks
 * - `«…»`  French guillemets
 * - `“…”`  English typographic double quotes
 * - `‘…’`  English typographic single quotes
 *
 * Inside a quoted region, a backslash (`\`) escapes the next byte, so
 * `\"` or `\\` do not terminate the region. This matches the convention
 * used by most modern formats (JSON, shell, RFC 7230 quoted-pair). The
 * escape itself is *not* decoded — it is preserved verbatim in the
 * output segment. Pass an empty `$escape` to disable escape handling.
 *
 * Unclosed quoted regions are tolerated: the remainder of the string is
 * returned as the final segment.
 *
 * @param string $input     The string to split.
 * @param string $separator The separator. An empty separator returns `[$input]`.
 * @param bool   $trim      Whether to trim whitespace around each segment (default: false).
 * @param string $escape    The byte that escapes the next byte inside a
 *                          quoted region. Default `\`. Pass `''` to disable.
 *
 * @return list<string> The list of segments.
 *
 * @example
 * ```php
 * splitOutsideQuotes('a;b;c', ';');                 // ['a', 'b', 'c']
 * splitOutsideQuotes('a; "b;c"; d', ';', true);     // ['a', '"b;c"', 'd']
 * splitOutsideQuotes('"a\"b";c', ';');              // ['"a\"b"', 'c']
 * splitOutsideQuotes('a«b;c»d;e', ';');             // ['a«b;c»d', 'e']
 * splitOutsideQuotes('lonely', ';');                // ['lonely']
 * splitOutsideQuotes('"unclosed; rest', ';');       // ['"unclosed; rest']
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.8
 *
 * @see unquote()
 * @see isQuoted()
 */
function splitOutsideQuotes
(
    string $input     ,
    string $separator ,
    bool   $trim      = false ,
    string $escape    = '\\'
)
:array
{
    if ( $separator === '' )
    {
        return [ $input ] ;
    }

    static $pairs =
    [
        "'" => "'" ,
        '"' => '"' ,
        '`' => '`' ,
        '«' => '»' ,
        '“' => '”' ,
        '‘' => '’' ,
    ];

    $openers = array_keys( $pairs ) ;
    $len     = strlen( $input     ) ;
    $sepLen  = strlen( $separator ) ;
    $escLen  = strlen( $escape    ) ;

    $closer  = null ;
    $start   = 0    ;
    $i       = 0    ;
    $result  = []   ;

    while ( $i < $len )
    {
        if ( $closer === null )
        {
            if ( substr_compare( $input , $separator , $i , $sepLen ) === 0 )
            {
                $segment  = substr( $input , $start , $i - $start ) ;
                $result[] = $trim ? trim( $segment ) : $segment ;
                $i       += $sepLen ;
                $start    = $i ;
                continue ;
            }

            foreach ( $openers as $opener )
            {
                $oLen = strlen( $opener ) ;
                if ( substr_compare( $input , $opener , $i , $oLen ) === 0 )
                {
                    $closer = $pairs[ $opener ] ;
                    $i     += $oLen ;
                    continue 2 ;
                }
            }
        }
        else
        {
            if ( $escLen > 0 && substr_compare( $input , $escape , $i , $escLen ) === 0 && $i + $escLen < $len )
            {
                $i += $escLen + 1 ;
                continue ;
            }

            $cLen = strlen( $closer ) ;
            if ( substr_compare( $input , $closer , $i , $cLen ) === 0 )
            {
                $closer = null ;
                $i     += $cLen ;
                continue ;
            }
        }
        $i++ ;
    }

    $segment  = substr( $input , $start ) ;
    $result[] = $trim ? trim( $segment ) : $segment ;

    return $result ;
}
