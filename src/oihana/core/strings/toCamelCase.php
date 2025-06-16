<?php

namespace oihana\core\strings ;

use oihana\enums\Char;

/**
 * Returns the camelcase representation of the specific expression.
 * @param string $word The expression to format.
 * @param array $separators The enumeration of separators to replace in the word expression.
 * @return string The new camelCase representation.
 */
function toCamelCase( string $word , array $separators = [ Char::UNDERLINE, Char::DASH , Char::SLASH ] ): string
{
    return lcfirst(str_replace(' ', '', ucwords(str_replace($separators, Char::SPACE, $word))));
}