<?php

declare(strict_types=1);

namespace oihana\core\strings ;

/**
 * Format an indented multi-line text block.
 *
 * @param array|string $input          Lines as array or multi-line string.
 * @param string|int   $indent         Indentation as string or number of spaces.
 * @param string       $separator      Line separator (defaults to PHP_EOL).
 * @param bool         $keepEmptyLines Whether to preserve empty lines (default: true).
 *
 * @return string A single string with the lines joined by $separator and indented.
 *
 * @example
 * Use an input array :
 * ```php
 * echo block
 * ([
 *     'if ($host ~* ^example\\.com$)',
 *     '{',
 *     '    rewrite ^(.*) https://www.example.com$1 permanent;',
 *     '    break',
 *     '}'
 * ] , 4 ) ;
 *
 * echo block(['a', 'b', 'c'], '-', 0); // "a-b-c"
 * ```
 *
 * Use an input string:
 * ```php
 * echo blockLines( "foo\nbar", '-> ' ) ;
 * // Output:
 * // -> foo
 * // -> bar
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function block( array|string $input , string|int $indent = '' , string $separator = PHP_EOL , bool $keepEmptyLines = true ) :string
{
    if ( $input === '' || $input === [] )
    {
        return '' ;
    }

    if( is_string( $input ) )
    {
        $input = preg_split('/\R/', $input, -1, $keepEmptyLines ? 0 : PREG_SPLIT_NO_EMPTY ) ;
    }
    else if ( !$keepEmptyLines )
    {
        $input = array_filter( $input , fn( string $line ) :bool => trim( $line ) !== '' ) ;
    }

    if ( is_int( $indent ) )
    {
        $indent = str_repeat(' ' , $indent ) ;
    }

    if ( $indent !== '' )
    {
        $input = array_map(
            fn( string $line ): string => $indent . $line ,
            $input
        );
    }

    return implode( $separator , $input ) ;
}