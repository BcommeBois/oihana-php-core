<?php

namespace oihana\core\env ;

/**
 * Checks if the PHP script is running in CLI mode (terminal, cron, Symfony Console…).
 *
 * Returns `true` if PHP is executed from the command line.
 *
 * @example
 * ```php
 * use function oihana\core\env\isCli;
 *
 * if (isCli())
 * {
 *     echo "CLI mode\n";
 * }
 * ```
 *
 * @return bool `true` if the script is running in CLI, `false` otherwise
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isCli(): bool
{
    static $cli = null;
    if ( $cli === null )
    {
        $cli = PHP_SAPI === 'cli';
    }
    return $cli ;
}