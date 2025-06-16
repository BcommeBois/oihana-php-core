<?php

namespace oihana\core\date ;

use DateTime;
use oihana\enums\Char;

/**
 * Indicates if the passed-in expression is a valid date with a specific format (default 'Y-m-d').
 * @param ?string $date The expression to evaluates.
 * @param string $format The date format (default 'Y-m-d').
 * @return bool Indicates if the passed-in expression is a valid timezone.
 */
function isDate( ?string $date , string $format = 'Y-m-d' ):bool
{
    if( is_string( $date ) && $date != Char::EMPTY )
    {
        $dateObj = DateTime::createFromFormat( $format , $date );
        return $dateObj && $dateObj->format($format) === $date;
    }
    return false ;
}