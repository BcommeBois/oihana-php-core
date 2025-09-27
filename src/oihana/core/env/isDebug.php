<?php

namespace oihana\core\env ;

/**
 * Checks if PHP is running in debug mode.
 *
 * @return bool `true` if PHP is running in debug mode, `false` otherwise
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isDebug(): bool
{
    static $debug = null ;
    if ( $debug === null )
    {
        $debug = (bool) ini_get('display_errors' ) ;
    }
    return $debug ;
}