<?php

namespace oihana\core\documents ;

use stdClass;
use Throwable;

use function oihana\core\strings\format;

/**
 * Recursively formats all string values in a document (array or object) by replacing placeholders with their values
 * found in the root document.
 *
 * This function uses the `format` function to replace placeholders in string values with the corresponding values
 * from the document. It supports nested keys with a custom separator (default is '.').
 *
 * Placeholders are defined by a prefix and suffix (default '{{' and '}}'), e.g. "{{key.subkey}}".
 *
 * Example:
 * ```php
 * $config =
 * [
 *     'dir'    => '/var/www/project',
 *     'htdocs' => '{{dir}}/htdocs',
 *     'env'    => 'production',
 *     'config' =>
 *      [
 *         'production' => [ 'url' => 'https://example.com' ]
 *      ],
 *     'api' => '{{config.{{env}}.url}}/api'
 *     'wordpress' => [
 *         'server' => [
 *             'subdomain' => 'www',
 *             'domain' => 'example.com',
 *             'url' => 'https://{{wordpress.server.subdomain}}.{{wordpress.server.domain}}/'
 *         ]
 *     ]
 * ];
 * $formatted = formatDocument($config);
 *
 * echo $formatted['htdocs'] ; // outputs: /var/www/project/htdocs
 * echo $formatted['wordpress']['server']['url'] ; // outputs: https://www.example.com/
 *
 * $formatted = formatDocument($config);
 * echo $formatted['api']; // outputs: https://example.com/api
 * ```
 *
 * @param array|object  $document  The document (array or object) to recursively format.
 * @param string        $prefix    Placeholder prefix (default '{{').
 * @param string        $suffix    Placeholder suffix (default '}}').
 * @param string        $separator Separator used in nested keys (default '.').
 * @param string|null   $pattern   Optional regex pattern to match placeholders.
 * @param callable|null $formatter Optional custom formatter callable.
 *
 * @return array|object A new formatted document with same structure and class.
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function formatDocument
(
    array|object $document ,
    string       $prefix    = '{{' ,
    string       $suffix    = '}}' ,
    string       $separator = '.'  ,
   ?string       $pattern   = null ,
   ?callable     $formatter = null
)
: array|object
{
    $root      = $document;
    $processed = [] ;

    $fn = function ( array|object $doc ) use ( &$fn , &$processed , $root , $prefix , $suffix , $separator , $pattern , $formatter ) :array|object
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
                    $result = new $class() ;
                }
                catch ( Throwable )
                {
                    $result = new stdClass() ;
                }
            }
            $processed[$id] = $result;
        }
        else
        {
            $result = $doc ; // Fallback (should never happen)
        }

        $applyFormat = fn( $val ) => $formatter !== null
                                   ? $formatter ( $val , $root , $prefix , $suffix , $separator , $pattern )
                                   : format     ( $val , $root , $prefix , $suffix , $separator , $pattern ) ;

        foreach ( $doc as $key => $value )
        {
            $formattedKey = $key;

            if ( ( ( is_array( $value ) && $value ) || ( is_object( $value ) && (array) $value ) ) )
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

        return $result ;
    };

    return $fn( $document ) ;
}