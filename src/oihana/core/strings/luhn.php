<?php

namespace oihana\core\strings ;

/**
 * Indicates if the passed-in string is a valid luhn code.
 * @param string $number The expression to evaluate.
 * @param bool $lazy Indicates if the validation is lazy (the none-numeric parameters are removed)
 * @return bool Indicates if the string is a luhn expression.
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