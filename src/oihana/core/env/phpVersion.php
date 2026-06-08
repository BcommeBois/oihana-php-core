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
// PHP function names are case-insensitive: this shadows the builtin \phpversion() inside the
// oihana\core\env namespace, where an unqualified phpversion() resolves here. Use \phpversion() for the native one.
function phpVersion() :string
{
    static $version = null ;
    if ( $version === null )
    {
        $version = PHP_VERSION ;
    }
    return $version;
}
