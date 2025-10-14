<?php

namespace oihana\core\json ;

/**
 * Get JSON type of a PHP value.
 *
 * @param mixed  $value   The value to evaluates.
 * @param string $default The default return value if the type is not a valid JSON type.
 *
 * @return string
 *
 * @package oihana\core\json
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function getJsonType( mixed $value , string $default = 'unknown' ): string
{
    return match( true )
    {
        is_null   ( $value ) => 'null'    ,
        is_bool   ( $value ) => 'boolean' ,
        is_int    ( $value ) => 'integer' ,
        is_float  ( $value ) => 'number'  ,
        is_string ( $value ) => 'string'  ,
        is_array  ( $value ) => 'array'   ,
        is_object ( $value ) => 'object'  ,
        default              => $default
    };
}