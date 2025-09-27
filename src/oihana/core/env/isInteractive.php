<?php

namespace oihana\core\env ;

/**
 * Checks if the script is running in an interactive CLI (php -a or terminal input).
 *
 * @return bool `true` if interactive CLI, `false` otherwise
 *
 * @example
 * ```php
 * use function oihana\core\env\isInteractive;
 *
 * if ( isInteractive() )
 * {
 *    echo "Interactive cli mode\n";
 * }
 * ```
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isInteractive(): bool
{
    static $interactive = null ;
    if ( $interactive === null )
    {
        $interactive = isCli() && function_exists( 'posix_isatty' ) && posix_isatty( STDIN ) ;
    }
    return $interactive;
}