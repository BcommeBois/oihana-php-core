<?php

namespace oihana\core\date ;

/**
 * Indicates if the passed-in expression is a valid timezone.
 * timezone_identifiers_list() requires PHP >= 5.2
 * @param ?string $timezone The timezone expression to evaluates.
 * @return bool Indicates if the passed-in expression is a valid timezone.
 */
function isValidTimezone( ?string $timezone = null ) :bool
{
    return in_array( $timezone , timezone_identifiers_list() ) ;
}