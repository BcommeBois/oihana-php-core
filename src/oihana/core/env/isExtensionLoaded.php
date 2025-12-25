<?php

namespace oihana\core\env ;

/**
 * Checks if a PHP extension is loaded.
 *
 * @return bool `true` if a PHP extension is loaded, `false` otherwise
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isExtensionLoaded( string $ext ) :bool
{
    return extension_loaded( $ext ) ;
}