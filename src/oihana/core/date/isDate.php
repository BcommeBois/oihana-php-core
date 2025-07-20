<?php

namespace oihana\core\date ;

use DateTime;

/**
 * Indicates if the passed-in expression is a valid date with a specific format (default 'Y-m-d').
 *
 * @param ?string $date The expression to evaluate.
 * @param string $format The date format (default 'Y-m-d').
 *
 * @return bool Indicates if the passed-in expression is a valid timezone.
 *
 * @example
 * ```php
 * var_dump(isDate('2025-07-20'));           // true
 * var_dump(isDate('20/07/2025', 'd/m/Y'));  // true
 * var_dump(isDate('invalid-date'));         // false
 * var_dump(isDate(null));                   // false
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isDate( ?string $date , string $format = 'Y-m-d' ):bool
{
    if( is_string( $date ) && $date != '' )
    {
        $dateObj = DateTime::createFromFormat( $format , $date );
        return $dateObj && $dateObj->format($format) === $date;
    }
    return false ;
}