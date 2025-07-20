<?php

namespace oihana\core\date ;

/**
 * Indicates if the passed-in expression is a valid timezone.
 *
 * Note: timezone_identifiers_list() requires PHP >= 5.2
 *
 * @param ?string $timezone The timezone expression to evaluates.
 *
 * @return bool Indicates if the passed-in expression is a valid timezone.
 *
 * @example
 * ```php
 * var_dump(isValidTimezone('Europe/Paris')); // true
 * var_dump(isValidTimezone('Invalid/Timezone')); // false
 * var_dum(isValidTimezone(null)); // false
 * ```
 *
 * @package oihana\core\date
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function isValidTimezone( ?string $timezone = null ) :bool
{
    return in_array( $timezone , timezone_identifiers_list() ) ;
}