<?php

namespace oihana\core\strings ;

/**
 * Encodes the specified uri according to RFC 3986.
 */
function urlencode( string $uri ): array|string
{
    $entities = [ '%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D' ];
    $replacements = [ '!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]" ] ;
    return str_replace( $entities , $replacements , \urlencode($uri) ) ;
}