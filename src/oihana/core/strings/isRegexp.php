<?php

namespace oihana\core\strings ;

/**
 * Indicates if the passed-in expression is a Regexp.
 * @param string $pattern
 * @return bool
 */
function isRegexp( string $pattern ) : bool
{
    return @preg_match($pattern, '') !== false;
}