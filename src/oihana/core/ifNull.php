<?php

namespace oihana\core ;

/**
 * Returns the given value if it is not null; otherwise, returns the default value.
 *
 * This function is useful to provide a fallback value when a variable might be null.
 *
 * @param mixed $value The value to evaluate.
 * @param mixed $default The default value to return if $value is null. Defaults to null.
 *
 * @return mixed Returns $value if it is not null; otherwise returns $default.
 *
 * @example
 * ```php
 * echo ifNull(null, 'default');   // Outputs: default
 * echo ifNull('hello', 'default'); // Outputs: hello
 * echo ifNull(0, 'default');       // Outputs: 0 (because 0 is not null)
 * ```
 */
function ifNull( mixed $value , mixed $default = null ): mixed
{
    return $value ?? $default ;
}