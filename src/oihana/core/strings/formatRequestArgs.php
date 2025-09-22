<?php

namespace oihana\core\strings ;

/**
 * Builds a query string (`?key=value&...`) from an associative array,
 * properly URL-encoding both keys and values.
 *
 * Optionally replaces the value of the `from` key with `"now"` if `$useNow` is set to `true`.
 * This is useful for formatting request parameters in URLs or APIs.
 *
 * @param array $params The associative array of parameters (e.g., `['from' => 'yesterday', 'limit' => 10]`).
 * @param bool  $useNow Whether to override the `'from'` key with `'now'` if it exists.
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
 * // Output: "?search=php%20functions&page=2"
 *
 * echo formatRequestArgs(['filter' => '{"key":"category","op":"eq","val":"5"}']);
 * // Output: "?filter=%7B%22key%22%3A%22category%22%2C%22op%22%3A%22eq%22%2C%22val%22%3A%225%22%7D"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function formatRequestArgs( array $params , bool $useNow = false ): string
{
    if ( empty( $params ) )
    {
        return '' ;
    }

    $str   = '' ;
    $first = true ;

    foreach ( $params as $key => $value )
    {
        if ( $key === 'from' && $useNow )
        {
            $value = 'now';
        }

        $key   = rawurlencode( (string) $key   ) ;
        $value = rawurlencode( (string) $value ) ;

        $str .= ($first ? '?' : '&') . $key . '=' . $value ;
        $first = false ;
    }

    return $str ;
}