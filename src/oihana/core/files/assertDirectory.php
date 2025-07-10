<?php

namespace oihana\core\files ;

use oihana\exceptions\DirectoryException;

/**
 * Asserts that a directory exists and is accessible.
 *
 * @param string|null $directory The path of the directory to check.
 * @return void
 * @throws DirectoryException If the directory path is null, empty, or if the directory does not exist or is not accessible.
 */
function assertDirectory( ?string $directory ): void
{
    if ( is_null( $directory ) )
    {
        throw new DirectoryException('The directory path must not be null.' ) ;
    }

    $directory = trim( $directory ) ;
    if ( empty( $directory ) )
    {
        throw new DirectoryException('The directory path must not be empty.');
    }

    if ( !is_dir( $directory ) )
    {
        throw new DirectoryException( sprintf('The path "%s" is not a valid directory.' , $directory ) ) ;
    }

    if ( !is_readable( $directory ) )
    {
        throw new DirectoryException( sprintf('The directory "%s" is not readable.', $directory ) );
    }
}