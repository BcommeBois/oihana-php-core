<?php

namespace oihana\core\documents ;

use SplObjectStorage;
use function oihana\core\strings\format;

/**
 * Mutates the given target document by formatting its string values in-place using values from a source document.
 *
 * @param array|object  &$target         The target document to format.
 * @param array|object  $source          The source document used for placeholder resolution.
 * @param string        $prefix          Placeholder prefix (default '{{').
 * @param string        $suffix          Placeholder suffix (default '}}').
 * @param string        $separator       Separator used in nested keys (default '.').
 * @param string|null   $pattern         Optional regex pattern to match placeholders.
 * @param callable|null $formatter       Optional custom formatter callable.
 * @param bool          $preserveMissing If true, preserves unresolved placeholders instead of removing them (default false).
 *
 * @return void
 */
function formatDocumentInPlace
(
    array|object &$target                  ,
    array|object  $source                  ,
    string        $prefix          = '{{'  ,
    string        $suffix          = '}}'  ,
    string        $separator       = '.'   ,
    ?string       $pattern         = null  ,
    ?callable     $formatter       = null  ,
    bool          $preserveMissing = false ,
)
: void
{
    $applyFormat = fn( $val ) => $formatter !== null
                 ? $formatter( $val , $source , $prefix , $suffix , $separator , $pattern , $preserveMissing )
                 : format    ( $val , $source , $prefix , $suffix , $separator , $pattern , $preserveMissing ) ;

    $visited = new SplObjectStorage();

    $recurse = function ( &$doc ) use ( &$recurse, $applyFormat, $prefix , &$visited )
    {
        if ( is_object( $doc ) )
        {
            if ( $visited->contains( $doc ) )
            {
                return;
            }
            $visited->attach( $doc ) ;
        }

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