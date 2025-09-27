<?php

namespace oihana\core\env ;

/**
 * Checks if the script is likely running from a cron job.
 *
 * @return bool `true` if likely a cron job, `false` otherwise
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isCron(): bool
{
    static $cron = null ;
    if ( $cron === null )
    {
        $cron = isCli() && empty( $_SERVER[ 'TERM' ] ) ;
    }
    return $cron ;
}