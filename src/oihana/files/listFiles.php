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
 * @param array{ pattern:null|array|string , filter:null|callable , followLinks:null|bool , includeDots:null|bool , order:null|string  , recursive:null|bool , sort:null|callable|string } $options
 *  <li><b>filter</b> : The optional function to filter all files, ex: <code>fn( $file ) => $file->getFileName()</code></li>
 *  <li><b>followLinks</b> : Indicates if the dot files are ignored (default false).</li>
 *  <li><b>includeDots</b> : Indicates if the dot files are included (default false).</li>
 *  <li><b>order</b> : The order of the file sorting : default 'ASC' or 'DESC'.
 *  <li><b>pattern</b> : A pattern (a regexp, a glob, or a string) or an array of patterns.</li>
 *  <li><b>recursive</b> : Indicates if all sub-directories are browsed (default false).</li>
 *  <li><b>sort</b> : The optional sort option to sort all files, ex: <code>fn( SplFileInfo $a, SplFileInfo $b ) => return strcmp($a->getRealPath(), $b->getRealPath())</code></li>
 * @return SplFileInfo[]
 * @throws DirectoryException
 * @examples
 * ```php
 *  // 1) Finder‑like ->sortByName()
 *  $files = listFiles('/var/www', ['sort' => 'name']);
 *
 *  // 2) Case‑insensitive name, descending
 *  $files = listFiles('/var/www', ['sort' => 'ci_name', 'order' => 'desc']);
 *
 *  // 3) Directories first, then alphabetical (type + name)
 *  $files = listFiles('/var/www', ['sort' => ['type', 'name']]);
 *
 *  // 4) Size descending with custom comparator
 *  $files = listFiles('/var/www', [
 *      'sort'  => fn(SplFileInfo $a, SplFileInfo $b) => $a->getSize() <=> $b->getSize(),
 *      'order' => 'desc',
 *  ]);
 *
 *  // 5) Recursive search for *.log and *.txt, ignore dot‑files, follow symlinks
 *  $files = listFiles('/var/log', [
 *      'pattern'    => ['*.log', '*.txt'],
 *      'recursive'   => true,
 *      'followLinks' => true,
 *  ]);
 *
 *  // 6) Include dot‑files and map to filenames only
 *  $names = listFiles('/tmp', [
 *      'includeDots' => true,
 *      'filter'    => fn(SplFileInfo $f) => $f->getFilename(),
 *  ]);
 *
 *  // 7) Mixed glob + regexp filter
 *  $files = listFiles('/data', [
 *      'pattern' => ['*.csv', '/^report_\d{4}\.xlsx$/i'],
 *  ]);
 *  ```
 * </code>
 * @see sortFiles()
 */
function listFiles( ?string $directory, array $options = [] ): array
{
    assertDirectory( $directory );

    $filter      = $options[ 'filter'      ] ?? null  ;
    $followLinks = $options[ 'followLinks' ] ?? false ;
    $includeDots = $options[ 'includeDots' ] ?? false ;
    $order       = $options[ 'order'       ] ?? 'asc' ;
    $patterns    = $options[ 'pattern'     ] ?? null  ;
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
                $match = isRegexp( $pattern )
                       ? (bool) preg_match($pattern, $fileName)
                       : fnmatch( $pattern , $fileName ) ;

                if ( $match ) break ;
            }

            if ( !$match )
            {
                continue;
            }
        }

        $files[] = new SplFileInfo( $file->getPathname() ) ;
    }

    if ( $sort )
    {
        sortFiles( $files , $sort , $order ) ;
    }

    if ( !empty($files) && is_callable( $filter ) )
    {
        $files = array_map( $filter , $files ) ;
    }

    return $files;
}