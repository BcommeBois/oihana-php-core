<?php

namespace oihana\core\env ;

/**
 * Finds where a PATH finds each program, as a shell would.
 *
 * For each name, the first file of that name in the directories of the PATH, in their order. A
 * file that is there but not executable is not found: the shell would not run it.
 *
 * Asked of a PATH other than the current one, it tells what a program started elsewhere — by
 * cron, by a service — will find, which is not what a session finds. Paired with
 * {@see completePath()}, it says whether completing a PATH is enough.
 *
 * @param array<string> $programs The program names, as a command line spells them.
 * @param string        $path     The PATH to look in.
 *
 * @return array<string, string|null> Each program, in the order given, with the file the PATH
 *                                    finds for it, or null.
 *
 * @example
 * ```php
 * use function oihana\core\env\programLocations;
 *
 * programLocations( [ 'tar' , 'nothing-by-that-name' ] , '/usr/bin:/bin' ) ;
 * // [ 'tar' => '/usr/bin/tar' , 'nothing-by-that-name' => null ]
 * ```
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.4.0
 */
function programLocations( array $programs , string $path ) :array
{
    $directories = array_filter( explode( ':' , $path ) , static fn( string $directory ) :bool => $directory !== '' ) ;
    $locations   = [] ;

    foreach( $programs as $program )
    {
        $locations[ $program ] = null ;

        foreach( $directories as $directory )
        {
            $file = rtrim( $directory , '/' ) . '/' . $program ;

            if( is_file( $file ) && is_executable( $file ) )
            {
                $locations[ $program ] = $file ;
                break ;
            }
        }
    }

    return $locations ;
}
