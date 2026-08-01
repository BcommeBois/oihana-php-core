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
    /** @var int|null $count */
    static $count = null;
    if ( $count === null )
    {
        if ( isLinux() && file_exists('/proc/cpuinfo' ) )
        {
            // Linux-only path; not exercised on the coverage host
            // @codeCoverageIgnoreStart
            $cpuinfo = file_get_contents('/proc/cpuinfo') ;
            $count   = $cpuinfo === false ? 1 : substr_count( $cpuinfo , 'processor' ) ; // unreadable /proc falls back like the branch below
            // @codeCoverageIgnoreEnd
        }
        else
        {
            $count = 1; // fallback
        }
    }
    return $count;
}