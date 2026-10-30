<?php

declare(strict_types=1);

namespace oihana\core\encoding ;

use Random\RandomException;

/**
 * Generates a password nobody chose and nobody has to remember: meant to be copied, not typed
 * from memory.
 *
 * ### The alphabet
 *
 * Letters and digits only, minus the five that are misread when a password is copied from a
 * screen or a notebook: `l`, `I`, `1`, `O`, `0`. That leaves 57 characters. No punctuation, so
 * the value travels through a shell argument, a TOML or INI string, a URL or a command-line tool
 * without anything having to escape it.
 *
 * ### The length
 *
 * 28 characters out of 57 is about 163 bits. The length is what carries the strength here, and
 * it costs nothing for a password that is copied. **A length under 16 is raised to 16**, silently:
 * the function answers with a usable password rather than an error, unlike {@see randomHex()},
 * which throws on a length it cannot honour.
 *
 * Each character is drawn with {@see random_int()}, a CSPRNG — never `rand()` or `shuffle()`,
 * which are predictable from a few samples.
 *
 * @param int $length How many characters, 16 at least.
 *
 * @return string The password: `max( 16 , $length )` characters of `[a-km-zA-HJ-NP-Z2-9]`.
 *
 * @throws RandomException If the system has no source of randomness.
 *
 * @example
 * ```php
 * use function oihana\core\encoding\randomPassword;
 *
 * randomPassword() ;     // e.g. 'pKx7DwqT4hLvZrc9Ym3nEuB8fGsa' (28 characters)
 * randomPassword( 40 ) ; // 40 characters
 * randomPassword( 4 ) ;  // 16 characters: the floor
 * ```
 *
 * @see randomHex()       A token of hexadecimal characters.
 * @see randomBase64Url() A shorter token at equivalent entropy, with `-` and `_`.
 *
 * @package oihana\core\encoding
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.4.0
 */
function randomPassword( int $length = 28 ) :string
{
    $alphabet = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789' ;

    $length = max( 16 , $length ) ;
    $last   = strlen( $alphabet ) - 1 ;

    $password = '' ;

    for ( $i = 0 ; $i < $length ; $i++ )
    {
        $password .= $alphabet[ random_int( 0 , $last ) ] ;
    }

    return $password ;
}
