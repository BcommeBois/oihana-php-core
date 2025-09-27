<?php

namespace oihana\core\env ;

/**
 * Indicates if the OS system is Mac.
 *
 * @return bool `true` if running on Mac, `false` otherwise
 *
 * @example
 * ```php
 * use function oihana\core\env\isMac;
 *
 * if ( isMac() )
 * {
 *     echo "Mac environment\n";
 * }
 * ```
 *
 * @package oihana\files
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isMac():bool
{
    static $isMac = null ;
    if ( $isMac === null )
    {
        $isMac = strtoupper( substr(PHP_OS , 0 , 6 ) ) === 'DARWIN' ;
    }
    return $isMac ;
}