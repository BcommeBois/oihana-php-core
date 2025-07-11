<?php

namespace oihana\files ;

use Exception;
use FilesystemIterator;
use oihana\files\exceptions\DirectoryException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Deletes a directory recursively.
 * @param ?string $directory The directory path to delete.
 * @return bool Returns true if the directory is removed.
 * @throws DirectoryException If the directory path is null, empty, or if the directory cannot be deleted.
 */
function deleteDirectory( ?string $directory ): bool
{
    assertWritableDirectory( $directory ) ;

    // Normalize the directory path to avoid issues with trailing slashes
    $directory = rtrim( $directory , DIRECTORY_SEPARATOR ) ;

    try
    {
        $iterator = new RecursiveDirectoryIterator( $directory, FilesystemIterator::SKIP_DOTS ) ;
        $files    = new RecursiveIteratorIterator( $iterator, RecursiveIteratorIterator::CHILD_FIRST ) ;

        foreach ( $files as $file )
        {
            $path = $file->getPathname();
            if ( empty( $path ) )
            {
                continue; // Skip if path is empty (shouldn't happen, but just in case)
            }

            if ( !is_writable( $path ) )
            {
                throw new DirectoryException(sprintf('The path "%s" is not writable.', $path ) );
            }

            if ( $file->isDir() )
            {
                if ( !@rmdir( $path ) )
                {
                    throw new DirectoryException( sprintf('Failed to remove directory "%s".' , $file->getRealPath() ) ) ;
                }
            }
            else
            {
                if ( !@unlink( $path ) )
                {
                    throw new DirectoryException(sprintf('Failed to remove file "%s".', $file->getRealPath()));
                }
            }
        }

        if ( !is_writable( $directory ) )
        {
            throw new DirectoryException(sprintf('The directory "%s" is not writable.' , $directory ) ) ;
        }

        if ( !@rmdir( $directory ) )
        {
            throw new DirectoryException(sprintf('Failed to remove directory "%s".', $directory));
        }
    }
    catch ( DirectoryException $e )
    {
        throw $e ;
    }
    catch ( Exception $e )
    {
        throw new DirectoryException( sprintf('An error occurred while deleting directory "%s": %s' , $directory , $e->getMessage() ) ) ;
    }

    return true ;
}