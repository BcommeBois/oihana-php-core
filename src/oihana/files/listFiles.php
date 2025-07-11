<?php

namespace oihana\files ;

use DirectoryIterator;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo ;

use oihana\enums\Char;
use oihana\files\exceptions\DirectoryException;
use function oihana\core\strings\isRegexp;

/**
 * List the files in a directory (non-recursive), ignoring dotfiles.
 * @param ?string $directory The directory path.
 * @param array{ patterns:null|array|string , callback:null|callable , followLinks:null|bool , includeDots:null|bool , recursive:null|bool , sort:null|callable|string } $options
 *  <li>"callback" : The optional callback to map all files, ex: <code>fn( $file ) => $file->getFileName()</code></li>
 *  <li>"followLinks" : Indicates if the dot files are ignored (default true).</li>
 *  <li>"includeDots" : Indicates if the dot files are included (default false).</li>
 *  <li>"patterns" : A pattern (a regexp, a glob, or a string) or an array of patterns.</li>
 *  <li>"recursive" : Indicates if all sub-directories are browsed (default false).</li>
 *  <li>"sort" : The optional sort callback or type (string) to sort all files, ex: <code>fn( SplFileInfo $a, SplFileInfo $b ) => return strcmp($a->getRealPath(), $b->getRealPath())</code></li>
 * @return SplFileInfo[]
 * @throws DirectoryException
 */
function listFiles( ?string $directory, array $options = [] ): array
{
    assertDirectory( $directory );

    $callback    = $options[ 'callback'    ] ?? null ;
    $followLinks = $options[ 'followLinks' ] ?? false ;
    $includeDots = $options[ 'includeDots' ] ?? false ;
    $patterns    = $options[ 'patterns'    ] ?? null ;
    $recursive   = $options[ 'recursive'   ] ?? false ;
    $sort        = $options[ 'sort'        ] ?? false ;

    $files = [];

    $iterator = $recursive
              ? new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $directory , ( $followLinks ? FilesystemIterator::FOLLOW_SYMLINKS : 0 ) | FilesystemIterator::SKIP_DOTS ) )
              : new DirectoryIterator( $directory ) ;

    foreach ($iterator as $file)
    {
        if ( !$file->isFile() )
        {
            continue;
        }

        $fileName = $file->getFilename();

        if ( !$includeDots && str_starts_with($fileName, Char::DOT ) )
        {
            continue;
        }

        $match = true ;

        if ( !empty( $patterns ) )
        {
            foreach ( (array) $patterns as $pattern )
            {
                if ( isRegexp( $pattern ) )
                {
                    $match = preg_match($pattern, $fileName);
                }
                else
                {
                    $match = fnmatch($pattern, $fileName);
                }

                if ( $match ) break ;
            }

            if ( !$match )
            {
                continue;
            }
        }

        $files[] = new SplFileInfo( $file->getPathname() ) ;
    }

    if ( $sort !== null )
    {
        if ( is_callable( $sort ) )
        {
            usort($files, $sort);
        }
        elseif ( $sort === 'name' )
        {
            usort($files, fn($a, $b) => strcmp($a->getFilename(), $b->getFilename()));
        }
        elseif ( $sort === 'mtime' )
        {
            usort($files, fn($a, $b) => $a->getMTime() <=> $b->getMTime());
        }
    }

    if ( !empty($files) && is_callable( $callback ) )
    {
        $files = array_map( $callback, $files ) ;
    }

    return $files;
}