<?php

namespace oihana\core\strings ;

/**
 * Validates whether a given string is a valid Luhn code (mod 10 checksum).
 *
 * The Luhn algorithm is commonly used to validate credit card numbers,
 * IMEI numbers, and other identification codes.
 *
 * @param string $number The input string to validate.
 * @param bool $lazy If true, non-digit characters will be removed before validation.
 *                   If false, the string must contain only digits.
 * @return bool Returns true if the input passes the Luhn check, false otherwise.
 *
 * @example
 * ```php
 * echo luhn("79927398713");           // Outputs: true (valid Luhn)
 * echo luhn("7992 7398 713", true);   // Outputs: true (valid Luhn, spaces removed)
 * echo luhn("1234");                  // Outputs: false (invalid Luhn)
 * ```
 */
function luhn( string $number , bool $lazy = false ): bool
{
    if( $lazy )
    {
        $number = preg_replace('/\D/', '', $number ) ;
    }

    if ( strlen( $number ) === 0 )
    {
        return false;
    }

    if ( !ctype_digit( $number ) )
    {
        return false;
    }

    $sum  = 0 ;
    $flag = 0 ;

    for( $i = strlen( $number ) - 1 ; $i >= 0 ; $i-- )
    {
        $add = $flag++ & 1 ? $number[$i] * 2 : $number[$i] ;
        $sum += $add > 9 ? $add - 9 : $add ;
    }

    return $sum % 10 === 0 ;
}