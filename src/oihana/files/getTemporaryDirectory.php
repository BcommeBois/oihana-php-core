<?php

namespace oihana\files ;

use oihana\files\exceptions\DirectoryException;

/**
 * Builds a path inside the system temporary directory.
 *
 * @param string|string[]|null $path Optional sub‑path(s) to append inside sys_get_temp_dir().
 * @param bool $assertable Whether to validate the final directory path. Defaults to false because the directory may not exist yet.
 *
 * @return string Normalised temporary directory path.
 *
 * @throws DirectoryException If validation is enabled and the path is invalid.
 */
function getTemporaryDirectory( string|array|null $path = null , bool $assertable = false ): string
{
    $segments = is_array($path) ? $path : ($path !== null ? [$path] : [] );
    array_unshift($segments , sys_get_temp_dir() ) ;
    return getDirectory( $segments , $assertable ) ;
}