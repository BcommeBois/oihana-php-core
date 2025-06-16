<?php

namespace oihana\core ;

/**
 * If the value is null returns the default value or return the value.
 * @param mixed $value The value to evaluates.
 * @param mixed $default The default value to return if $value is null.
 * @return mixed
 */
function ifNull( mixed $value , mixed $default = null ): mixed
{
    return $value ?? $default;
}