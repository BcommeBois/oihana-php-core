<?php

namespace oihana\core\strings ;

/**
 * Encodes the specified URI according to RFC 3986.
 *
 * This function applies PHP's built-in urlencode() and then
 * decodes certain reserved characters back to their literal forms,
 * matching RFC 3986 requirements.
 *
 * @param string $uri The URI string to encode.
 * @return string The RFC 3986 compliant encoded URI string.
 *
 * @example
 * ```php
 * echo urlencode("https://example.com/foo?bar=baz&qux=1");
 * // Outputs: https%3A%2F%2Fexample.com%2Ffoo%3Fbar%3Dbaz%26qux%3D1
 *
 * echo urlencode("hello world!");
 * // Outputs: hello%20world!
 * ```
 */
function urlencode( string $uri ): array|string
{
    $entities = [ '%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D' ];
    $replacements = [ '!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]" ] ;
    return str_replace( $entities , $replacements , \urlencode($uri) ) ;
}