<?php

namespace oihana\core\strings ;

use InvalidArgumentException;

/**
 * Pads a UTF-8 string to a certain length using a specified padding string and type.
 *
 * If the source string is null, it is treated as an empty string.
 * The padding uses grapheme clusters to correctly handle multibyte characters.
 *
 * @param ?string $source The input string or null.
 * @param int     $size   Desired length after padding (in grapheme clusters).
 * @param string  $pad    The string to use for padding (cannot be empty).
 * @param int     $type   One of STR_PAD_LEFT, STR_PAD_RIGHT, STR_PAD_BOTH.
 *
 * @return string The padded string.
 *
 * @throws InvalidArgumentException If the padding type is invalid or $pad is empty.
 *
 * @example
 * ```php
 * use function oihana\core\strings\pad;
 *
 * echo pad('hello', 10, ' ', STR_PAD_RIGHT); // Outputs: "hello     "
 * echo pad('hello', 10, '☺', STR_PAD_LEFT);  // Outputs: "☺☺☺☺☺hello"
 * echo pad('hello', 10, 'ab', STR_PAD_BOTH); // Outputs: "ababahelloaba"
 * ```
 *
 * @package oihana\core\strings
 * @author Marc Alcaraz (ekameleon)
 * @since 1.0.0
 */
function pad( ?string $source , int $size , string $pad , int $type ) :string
{
    $source = $source ?? '' ;

    if ( $pad === '' )
    {
        throw new InvalidArgumentException('Padding string cannot be empty.' ) ;
    }

    if ( !mb_check_encoding($pad, 'UTF-8') )
    {
        throw new InvalidArgumentException('Padding is an invalid UTF-8 string.');
    }

    $length = grapheme_strlen( $source ) ;

    if ( $size <= $length )
    {
        return $source ;
    }

    $padLen      = grapheme_strlen($pad);
    $freeLen     = $size - $length;
    $remainder   = $freeLen % $padLen;
    $repeatCount = intdiv( $freeLen , $padLen ) ;
    $padPartial  = $remainder ? slice( $pad , 0 , $remainder ) : '' ;

    switch ( $type )
    {
        case STR_PAD_RIGHT :
        {
            return append ($source , str_repeat( $pad , $repeatCount ) . $padPartial ) ;
        }
        case STR_PAD_LEFT  :
        {
            return prepend( $source , str_repeat( $pad , $repeatCount ) . $padPartial ) ;
        }
        case STR_PAD_BOTH  :
        {
            $rightLen = ceil($freeLen / 2);
            $leftLen  = $freeLen - $rightLen;

            $padLeft  = '';
            $padRight = '';

            while ( grapheme_strlen( $padLeft ) < $leftLen )
            {
                $padLeft .= $pad;
            }

            while ( grapheme_strlen( $padRight ) < $rightLen )
            {
                $padRight .= $pad;
            }

            $padLeft  = grapheme_substr($padLeft, 0, $leftLen);
            $padRight = grapheme_substr($padRight, 0, $rightLen);

            return $padLeft . $source . $padRight;
        }
        default :
        {
            throw new InvalidArgumentException('Invalid padding type.') ;
        }
    }
}