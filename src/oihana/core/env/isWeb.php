<?php

namespace oihana\core\env ;

/**
 * Checks if the PHP script is running in a Web server context (not CLI).
 *
 * Returns `true` if PHP is executed via HTTP (e.g., Slim, Symfony HTTP, WordPress).
 *
 * @return bool `true` if the script is running in a web context, `false` otherwise
 *
 * @example
 * ```php
 * use function oihana\core\env\isWeb;
 *
 * if (isWeb())
 * {
 *    echo "Web mode\n";
 * }
 * ```
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isWeb(): bool
{
    static $web = null ;
    if ( $web === null )
    {
        $web = !isCli() ;
    }
    return $web ;
}