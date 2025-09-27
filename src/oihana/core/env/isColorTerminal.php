<?php

namespace oihana\core\env ;

/**
 * Checks if the CLI supports colors.
 *
 * @return bool `true` if the CLI support colors, `false` otherwise
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isColorTerminal(): bool
{
    static $hasColor = null;
    if ( $hasColor === null )
    {
        $hasColor = isCli() && function_exists( 'posix_isatty' ) && posix_isatty( STDOUT ) ;
    }
    return $hasColor ;
}