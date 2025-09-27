<?php

namespace oihana\core\env ;

/**
 * Checks if the operating system is **not Windows, Mac, or Linux**.
 *
 * @return bool `true` if the OS is neither Windows, Mac, nor Linux; `false` otherwise
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function isOtherOS():bool
{
    return !isWindows() && !isLinux() && !isMac();
}