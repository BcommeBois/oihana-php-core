<?php

namespace oihana\core\files ;

use oihana\exceptions\DirectoryException;

/**
 * Asserts that a directory exists and is readable and writable.
 * @param string|null $directory The path of the directory to check.
 * @return void
 * @throws DirectoryException If the directory path is null, empty, or if the directory does not exist or is not accessible.
 */
function assertWritableDirectory( ?string $directory ): void
{
    assertDirectory( $directory ) ;
    if ( !is_writable( $directory ) )
    {
        throw new DirectoryException( sprintf('The directory "%s" is not writable.' , $directory ) ) ;
    }
}