<?php

namespace oihana\core\strings ;

/**
 * Converts a camelCased string to a hyphenated (kebab-case) string.
 *
 * @param string|null $source The string to hyphenate.
 * @return string The hyphenated string.
 *
 * @example
 * echo hyphenate("helloWorld"); // Outputs: "hello-world"
 */
function hyphenate( ?string $source ): string
{
    if ( !is_string( $source ) || $source === '' )
    {
        return '' ;
    }
    return preg_replace_callback
    (
        '/[A-Z]/',
        function ($matches) use ( $source )
        {
            $pos = strpos( $source , $matches[0] ) ;
            return $pos === 0 ? strtolower($matches[0]) : '-' . strtolower($matches[0]);
        },
        $source
    );
}