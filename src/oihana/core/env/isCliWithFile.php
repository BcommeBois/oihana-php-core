<?php

namespace oihana\core\env ;

/**
 * Checks if the PHP script is running in CLI mode **and** was launched with an actual file.
 *
 * Returns `true` if PHP is executed from the command line and `$_SERVER['argv'][0]` points to an existing file.
 *
 * @example
 * ```php
 * use function oihana\core\env\isCliWithFile;
 *
 * if (isCliWithFile())
 * {
 *     echo "CLI mode with script file\n";
 * }
 * else
 * {
 *     echo "Not a CLI script with a file\n";
 * }
 * ```
 *
 * @return bool `true` if running in CLI with a real script file, `false` otherwise
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isCliWithFile(): bool
{
    /** @var bool|null $cliFile */
    static $cliFile = null ;
    if ( $cliFile === null )
    {
        $argv    = $_SERVER[ 'argv' ] ?? null ;
        $argv0   = is_array( $argv ) ? ( $argv[ 0 ] ?? null ) : null ;
        $cliFile = isCli() && is_string( $argv0 ) && file_exists( $argv0 ) ;
    }
    return $cliFile ;
}