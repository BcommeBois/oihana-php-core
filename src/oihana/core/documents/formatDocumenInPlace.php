<?php

namespace oihana\core\documents ;

use function oihana\core\strings\format;

/**
 * Mutates the given target document by formatting its string values in-place using values from a source document.
 *
 * @param array|object  &$target    The target document to format.
 * @param array|object  $source     The source document used for placeholder resolution.
 * @param string        $prefix     Placeholder prefix (default '{{').
 * @param string        $suffix     Placeholder suffix (default '}}').
 * @param string        $separator  Separator used in nested keys (default '.').
 * @param string|null   $pattern    Optional regex pattern to match placeholders.
 * @param callable|null $formatter  Optional custom formatter callable.
 *
 * @return void
 */
function formatDocumentInPlace
(
    array|object &$target ,
    array|object  $source ,
    string        $prefix     = '{{' ,
    string        $suffix     = '}}' ,
    string        $separator  = '.'  ,
    ?string       $pattern    = null ,
    ?callable     $formatter  = null
)
: void
{
    $applyFormat = fn( $val ) => $formatter !== null
                 ? $formatter( $val , $source , $prefix , $suffix , $separator , $pattern )
                 : format    ( $val , $source , $prefix , $suffix , $separator , $pattern ) ;

    $recurse = function ( &$doc ) use ( &$recurse, $applyFormat, $prefix )
    {
        foreach ( $doc as $key => &$value )
        {
            if ( is_array( $value ) || ( is_object( $value ) && (array) $value ) )
            {
                $recurse( $value );
            }
            else if ( is_string( $value ) && str_contains( $value , $prefix ) )
            {
                $value = $applyFormat( $value );
            }
        }
    };

    $recurse( $target );
}