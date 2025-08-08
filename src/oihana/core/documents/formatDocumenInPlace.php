<?php

namespace oihana\core\documents ;

use SplObjectStorage;
use function oihana\core\accessors\getKeyValue;
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
 * @param callable|null $formatter       Optional custom formatter callable with signature
 *                                        `fn(string $value, array|object $source, string $prefix, string $suffix, string $separator, ?string $pattern, bool $preserveMissing): string`
 * @param bool          $preserveMissing If true, preserves unresolved placeholders instead of removing them (default false).
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
 * formatDocumentInPlace($target, $source);
 *
 * // Result:
 * // $target['host']    === 'localhost' (string)
 * // $target['port']    === 8080 (int)
 * // $target['enabled'] === false (bool)
 * // $
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

    $recurse = function ( &$doc ) use (&$recurse, $applyFormat, $prefix, $preserveMissing , $suffix, $separator, $source, &$visited)
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
        }
    };

    $recurse( $target );
}