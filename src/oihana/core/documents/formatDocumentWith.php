<?php

namespace oihana\core\documents ;

use stdClass;

use Throwable;
use function oihana\core\strings\format;

/**
 * Formats a document (array or object) using placeholders resolved from another source document.
 *
 * @param array<array-key, mixed>|object $target        The target document to format.
 * @param array<array-key, mixed>|object $source        The source document used for placeholder resolution.
 * @param string                       $prefix          Placeholder prefix (default '{{').
 * @param string                       $suffix          Placeholder suffix (default '}}').
 * @param non-empty-string             $separator       Separator used in nested keys (default '.').
 * @param string|null                  $pattern         Optional regex pattern to match placeholders.
 * @param (callable(string, array<array-key, mixed>|object, string, string, string, string|null, bool): string)|null $formatter Optional custom formatter with signature: `function(string $value, array|object $source, string $prefix, string $suffix, string $separator, ?string $pattern, bool $preserveMissing): string`
 * @param bool                         $preserveMissing If true, preserves unresolved placeholders instead of removing them (default false).
 *
 * @return array<array-key, mixed>|object A new document with the same structure and class as `$target`, where all string placeholders have been resolved using `$source`.
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
 *     [
 *         'prod' => [ 'url' => 'https://example.com' ]
 *     ]
 * ];
 *
 * $target =
 * [
 *     'htdocs' => '{{base_dir}}/htdocs',
 *     'api'    => '{{config.{{env}}.url}}/api'
 * ];
 *
 * $result = formatDocumentWith($target, $source);
 * echo $result['htdocs']; // /var/www/htdocs
 * echo $result['api'];    // https://example.com/api
 * ```
 */
function formatDocumentWith
(
    array|object  $target                  ,
    array|object  $source                  ,
    string        $prefix          = '{{'  ,
    string        $suffix          = '}}'  ,
    string        $separator       = '.'   ,
    ?string       $pattern         = null  ,
    ?callable     $formatter       = null  ,
    bool          $preserveMissing = false ,
)
: array|object
{
    $processed = [];

    $fn = function ( array|object $doc ) use ( &$fn, &$processed, $source, $prefix, $suffix, $separator, $pattern, $formatter , $preserveMissing ): array|object
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
        else
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

        $applyFormat = fn( string $val ) :string => $formatter !== null
            ? $formatter( $val , $source , $prefix , $suffix , $separator , $pattern , $preserveMissing )
            : format    ( $val , $source , $prefix , $suffix , $separator , $pattern , $preserveMissing ) ;

        foreach ( is_array( $doc ) ? $doc : get_object_vars( $doc ) as $key => $value )
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
                do
                {
                    $prev      = $formatted ;
                    $formatted = $applyFormat( $prev ) ;
                }
                while ( $formatted !== $prev && str_contains( $formatted , $prefix ) );
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