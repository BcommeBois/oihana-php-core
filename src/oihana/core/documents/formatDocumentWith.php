<?php

namespace oihana\core\documents ;

use stdClass;

use Throwable;
use function oihana\core\strings\format;

/**
 * Formats a document (array or object) using placeholders resolved from another source document.
 *
 * @param array|object  $target     The target document to format.
 * @param array|object  $source     The source document used for placeholder resolution.
 * @param string        $prefix     Placeholder prefix (default '{{').
 * @param string        $suffix     Placeholder suffix (default '}}').
 * @param string        $separator  Separator used in nested keys (default '.').
 * @param string|null   $pattern    Optional regex pattern to match placeholders.
 * @param callable|null $formatter  Optional custom formatter callable.
 *
 * @return array|object A new formatted document (same structure and class as $target).
 *
 * @see formatDocument()
 *
 * @example
 * ```php
 * $source =
 * [
 *     'base_dir' => '/var/www',
 *     'env'      => 'prod',
 *     'config'   =>
 *      [
 *         'prod' => [ 'url' => 'https://example.com' ]
 *      ]
 * ];
 *
 * $target =
 * [
 *     'htdocs' => '{{base_dir}}/htdocs',
 *     'api'    => '{{config.{{env}}.url}}/api'
 * ];
 *
 * $result = formatDocumentWith($target, $source);
 *
 * echo $result['htdocs']; // /var/www/htdocs
 * echo $result['api'];    // https://example.com/api
 * ```
 */
function formatDocumentWith
(
    array|object  $target ,
    array|object  $source ,
    string        $prefix     = '{{' ,
    string        $suffix     = '}}' ,
    string        $separator  = '.'  ,
    ?string       $pattern    = null ,
    ?callable     $formatter  = null
)
: array|object
{
    $processed = [];

    $fn = function ( array|object $doc ) use ( &$fn, &$processed, $source, $prefix, $suffix, $separator, $pattern, $formatter ): array|object
    {
        if ( is_object( $doc ) )
        {
            $id = spl_object_id( $doc ) ;
            if ( array_key_exists( $id , $processed ) )
            {
                return $processed[ $id ];
            }
        }

        if ( is_array( $doc ) )
        {
            $result = [];
        }
        else if ( is_object( $doc ) )
        {
            $class = get_class($doc);

            if ( $class === 'stdClass' || str_starts_with( $class , 'class@anonymous' ) )
            {
                $result = new stdClass();
            }
            else
            {
                try
                {
                    $result = new $class();
                }
                catch( Throwable $exception )
                {
                    $result = new stdClass();
                }
            }

            $processed[ spl_object_id( $doc ) ] = $result;
        }
        else
        {
            return $doc; // scalar or null
        }

        $applyFormat = fn( $val ) => $formatter !== null
            ? $formatter( $val , $source , $prefix , $suffix , $separator , $pattern )
            : format    ( $val , $source , $prefix , $suffix , $separator , $pattern ) ;

        foreach ( $doc as $key => $value )
        {
            $formattedKey = $key;

            if ( ( is_array( $value ) && $value ) || ( is_object( $value ) && (array) $value ) )
            {
                $formatted = $fn( $value ) ;
            }
            else
            {
                $formatted = $value ;
            }

            if ( is_string( $formatted ) && $formatted !== '' && str_contains( $formatted , $prefix ) )
            {
                $formatted = $applyFormat( $formatted ) ;
            }

            if ( is_array( $result ) )
            {
                $result[ $formattedKey ] = $formatted ;
            }
            else
            {
                $result->$formattedKey = $formatted ;
            }
        }

        return $result;
    };

    return $fn( $target );
}