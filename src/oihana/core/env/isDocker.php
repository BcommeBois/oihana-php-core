<?php

namespace oihana\core\env ;

/**
 * Checks if PHP is running inside a Docker container.
 *
 * @return bool `true` if running in Docker, `false` otherwise
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isDocker(): bool
{
    static $docker = null ;
    if ( $docker === null )
    {
        $docker = file_exists( '/.dockerenv' ) || file_exists( '/.dockerinit' ) ;
    }
    return $docker ;
}