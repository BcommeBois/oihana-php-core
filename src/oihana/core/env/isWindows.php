<?php

namespace oihana\core\env ;

/**
 * Indicates if the OS system is Windows.
 *
 * @return bool `true` if running on Windows, `false` otherwise
 *
 * @example
 * ```php
 * use function oihana\core\env\isWindows;
 *
 * if ( isWindows() )
 * {
 *     echo "Windows environment\n";
 * }
 * ```
 *
 * @package oihana\files
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isWindows():bool
{
    static $isWindows = null;
    if ( $isWindows === null )
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
    return $isWindows;
}