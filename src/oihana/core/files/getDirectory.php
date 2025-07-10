<?php

namespace oihana\core\files ;

use oihana\exceptions\DirectoryException;

/**
 * Returns a normalized directory path by trimming trailing directory separators.
 *
 * This function verifies that the directory exists and is accessible, then returns
 * the directory path with any trailing directory separators removed.
 *
 * @param string $directory The directory path to process
 * @return string|null The normalized directory path, or null if the directory is invalid
 * @throws DirectoryException If the directory does not exist or is not accessible
 */
function getDirectory( string $directory ): ?string
{
    assertDirectory( $directory ) ;
    return rtrim( $directory , DIRECTORY_SEPARATOR ) ;
}