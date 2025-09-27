<?php

namespace oihana\core\env ;

/**
 * Returns the current PHP version.
 *
 * @example
 * ```php
 * use function oihana\core\env\phpVersion;
 *
 * echo phpVersion() ;
 * ```
 *
 * @return string The current PHP version.
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function phpVersion(): string
{
    static $version = null ;
    if ( $version === null )
    {
        $version = PHP_VERSION ;
    }
    return $version;
}
