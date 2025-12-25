<?php

namespace oihana\core\env ;

/**
 * Indicates if the OS system is Linux.
 *
 * @return bool `true` if running on Linux, `false` otherwise
 *
 * @example
 * ```php
 * use function oihana\core\env\isLinux;
 *
 * if ( isLinux() )
 * {
 *     echo "Linux environment\n";
 * }
 * ```
 *
 * @package oihana\files
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isLinux(): bool
{
    static $isLinux = null;
    if ( $isLinux === null )
    {
        $isLinux = strncasecmp(PHP_OS , 'LINUX' , 5 ) === 0 ;
    }
    return $isLinux;
}