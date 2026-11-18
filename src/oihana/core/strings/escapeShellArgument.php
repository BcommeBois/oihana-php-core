<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Quotes a value so a POSIX shell passes it to the program exactly as it is.
 *
 * The value is wrapped in single quotes, the one quoting where the shell interprets nothing:
 * no `$` expansion, no command substitution with backquotes, no backslash escape. A single
 * quote inside the value closes the quoting, is written escaped, and reopens it: `'\''`.
 *
 * Unlike PHP's `escapeshellarg()`, the result does not depend on the current locale. Once
 * `setlocale( LC_ALL , '' )` has been called in a `C` locale, `escapeshellarg()` drops every
 * byte that is not ASCII without a word, and `'Café'` becomes `'Caf'`. Here the bytes are kept
 * as they are.
 *
 * A NUL byte cannot be part of a command-line argument: the value is refused rather than
 * silently cut at that byte.
 *
 * @param string $value The raw value.
 *
 * @return string The value between single quotes, ready to be placed on a shell command line.
 *
 * @throws InvalidArgumentException If the value holds a NUL byte.
 *
 * @example
 * ```php
 * use function oihana\core\strings\escapeShellArgument;
 *
 * echo escapeShellArgument( 'value' ) ;     // 'value'
 * echo escapeShellArgument( 'a$HOME`id`' ) ; // 'a$HOME`id`'
 * echo escapeShellArgument( "it's" ) ;      // 'it'\''s'
 * echo escapeShellArgument( 'Café' ) ;      // 'Café'
 * echo escapeShellArgument( '' ) ;          // ''
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.4.0
 */
function escapeShellArgument( string $value ) :string
{
    if( str_contains( $value , "\0" ) )
    {
        throw new InvalidArgumentException( 'A shell argument cannot hold a NUL byte.' ) ;
    }

    return "'" . str_replace( "'" , "'\\''" , $value ) . "'" ;
}
