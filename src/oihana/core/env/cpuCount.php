<?php

namespace oihana\core\env ;

/**
 * Returns the number of CPUs available (if detectable).
 *
 * @return int The number of CPUs available (if detectable), null otherwise.
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function cpuCount(): int
{
    static $count = null;
    if ( $count === null )
    {
        if ( isLinux() && file_exists('/proc/cpuinfo' ) )
        {
            $count = substr_count(file_get_contents('/proc/cpuinfo'), 'processor');
        }
        else
        {
            $count = 1; // fallback
        }
    }
    return $count;
}