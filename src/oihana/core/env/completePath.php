<?php

namespace oihana\core\env ;

/**
 * Completes a PATH with the standard directories it lacks, added at its end.
 *
 * A program started by cron, by a systemd unit or by any other way than a login shell does not
 * receive the PATH of a session. Cron, on many systems, gives `/usr/bin:/bin` and nothing more:
 * a program installed in `/usr/local/bin` is found by hand and missing at night, and what fails
 * is whatever needed it, often far from the cause. Some systems mend it — Ubuntu's cron reads
 * `/etc/environment` — and others do not, so a program that depends on its PATH should not trust
 * the one it is given.
 *
 * Nothing is removed or moved: the standard directories come after those already there, so a
 * program an administrator put earlier in the PATH still wins. Empty segments are dropped, and a
 * directory is never listed twice by this function.
 *
 * @param string $path     The PATH as received; empty when there is none.
 * @param string $standard The directories a PATH should hold, in their order, as a PATH. By
 *                         default the one Debian and Ubuntu give a login session.
 *
 * @return string The PATH, completed.
 *
 * @example
 * ```php
 * use function oihana\core\env\completePath;
 *
 * completePath( '/usr/bin:/bin' ) ;
 * // '/usr/bin:/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/sbin'
 *
 * putenv( 'PATH=' . completePath( (string) getenv( 'PATH' ) ) ) ;
 * ```
 *
 * @package oihana\core\env
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.4.0
 */
function completePath( string $path , string $standard = '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin' ) :string
{
    $directories = array_values( array_filter( explode( ':' , $path ) , static fn( string $directory ) :bool => $directory !== '' ) ) ;

    foreach( explode( ':' , $standard ) as $directory )
    {
        if( $directory !== '' && !in_array( $directory , $directories , true ) )
        {
            $directories[] = $directory ;
        }
    }

    return implode( ':' , $directories ) ;
}
