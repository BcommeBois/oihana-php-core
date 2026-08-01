<?php

namespace oihana\core\documents ;

use Closure;
use SplObjectStorage;
use function oihana\core\accessors\getKeyValue;
use function oihana\core\strings\format;

/**
 * Hydrates or formats a document (array or object) in place by replacing placeholders
 * with corresponding values from a source document.
 *
 * @param array<array-key, mixed>|object &$target       The target document to format.
 * @param array<array-key, mixed>|object $source        The source document used for placeholder resolution.
 * @param string                       $prefix          Placeholder prefix (default '{{').
 * @param string                       $suffix          Placeholder suffix (default '}}').
 * @param non-empty-string             $separator       Separator used in nested keys (default '.').
 * @param string|null                  $pattern         Optional regex pattern to match placeholders.
 * @param (callable(string, array<array-key, mixed>|object, string, string, string, string|null, bool): string)|null $formatter Optional custom formatter callable with signature
 *                                        `fn(string $value, array|object $source, string $prefix, string $suffix, string $separator, ?string $pattern, bool $preserveMissing): string`
 * @param bool                         $preserveMissing If true, preserves unresolved placeholders instead of removing them (default false).
 *
 * @return void
 *
 * @example
 * ```php
 * $target =
 * [
 *     'host'        => '{{server.name}}',
 *     'port'        => '{{server.port}}',
 *     'enabled'     => '{{feature.enabled}}',
 *     'description' => 'Connect to {{server.name}} on port {{server.port}}',
 * ];
 *
 * $source =
 * [
 *     'server' => [
 *         'name' => 'localhost',
 *         'port' => 8080,
 *     ],
 *     'feature' =>
 *     [
 *         'enabled' => false,
 *     ],
 * ];
 *
 * resolvePlaceholders($target, $source);
 *
 * // Result:
 * // $target['host']    === 'localhost' (string)
 * // $target['port']    === 8080 (int)
 * // $target['enabled'] === false (bool)
 * // $
 */
function resolvePlaceholders
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
    $applyFormat = fn( string $val ) :string => $formatter !== null
                 ? $formatter( $val , $source , $prefix , $suffix , $separator , $pattern , $preserveMissing )
                 : format    ( $val , $source , $prefix , $suffix , $separator , $pattern , $preserveMissing ) ;

    $visited = new SplObjectStorage();

    /** @var Closure(array<array-key, mixed>|object): void $recurse */
    $recurse = function ( array|object &$doc ) use (&$recurse, $applyFormat, $prefix, $preserveMissing , $suffix, $separator, $source, &$visited) :void
    {
        if ( is_object( $doc ) )
        {
            if ( $visited->contains( $doc ) )
            {
                return;
            }
            $visited->attach( $doc ) ;
        }

        // Iterating the keys keeps the by-reference write working on both shapes : an object
        // cannot be walked with `foreach ( $doc as $k => &$v )` without losing static typing.
        $keys = is_array( $doc ) ? array_keys( $doc ) : array_keys( get_object_vars( $doc ) ) ;

        foreach ( $keys as $key )
        {
            if ( is_array( $doc ) )
            {
                $value = &$doc[ $key ] ;
            }
            else
            {
                $value = &$doc->{ $key } ;
            }

            if ( is_array( $value ) || ( is_object( $value ) && (array) $value ) )
            {
                $recurse( $value );
            }
            else if ( is_string( $value ) && str_contains( $value , $prefix ) )
            {
                $exactPatternRegex = '/^' . preg_quote($prefix, '/') . '([a-zA-Z0-9_.\-\[\]]+)' . preg_quote($suffix, '/') . '$/' ;

                if ( preg_match( $exactPatternRegex, $value, $matches ) )
                {
                    $keyName = $matches[1];

                    $replacement = getKeyValue( $source , $keyName , null , $separator ) ;

                    if ( $replacement === null )
                    {
                        $value = $preserveMissing ? $value : '' ;
                    }
                    else
                    {
                        $value = $replacement ;
                    }
                }
                else
                {
                    $value = $applyFormat( $value ) ;
                }
            }

            unset( $value ) ;
        }
    };

    $recurse( $target ) ;
}