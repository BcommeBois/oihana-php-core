<?php

namespace oihana\core\strings ;

/**
 * Builds a query string (`?key=value&...`) from an associative array.
 *
 * Optionally replaces the value of the `from` key with `"now"` if `$useNow` is set to `true`.
 * This is useful for formatting request parameters in URLs or APIs.
 *
 * @param array $params The associative array of parameters (e.g., `['from' => 'yesterday', 'limit' => 10]`).
 * @param bool $useNow Whether to override the `'from'` key with `'now'` if it exists.
 *
 * @return string A URL-compatible query string, starting with `?`, or an empty string if `$params` is empty.
 *
 * @example
 * ```php
 * echo formatRequestArgs(['from' => '2023-01-01', 'limit' => 10]);
 * // Output: "?from=2023-01-01&limit=10"
 *
 * echo formatRequestArgs(['from' => 'yesterday', 'limit' => 5], true);
 * // Output: "?from=now&limit=5"
 *
 * echo formatRequestArgs([]);
 * // Output: ""
 *
 * echo formatRequestArgs(['search' => 'php functions', 'page' => 2]);
 * // Output: "?search=php functions&page=2"
 * ```
 */
function formatRequestArgs( array $params, bool $useNow = false ): string
{
    $str = "" ;

    if( count( $params ) > 0 )
    {
        $first = true;

        foreach( $params as $key => $value )
        {
            if( ( $key == "from" ) && $useNow )
            {
                $value = "now" ;
            }

            if( $first )
            {
                $str  .= "?" ;
                $first = false;
            }
            else
            {
                $str .= "&" ;
            }

            $str .= $key . "=" . $value ;
        }
    }

    return $str;
}