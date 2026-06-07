<?php

namespace oihana\core\env ;

/**
 * Checks if a PHP extension is loaded.
 *
 * @param string $ext The case-insensitive name of the extension (e.g. `'intl'`, `'bcmath'`).
 *
 * @return bool `true` if a PHP extension is loaded, `false` otherwise
 *
 * @example
 * ```php
 * use function oihana\core\env\isExtensionLoaded;
 *
 * if ( isExtensionLoaded( 'intl' ) )
 * {
 *     // safe to use grapheme_* / Normalizer
 * }
 * ```
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isExtensionLoaded( string $ext ) :bool
{
    return extension_loaded( $ext ) ;
}