<?php

namespace oihana\core ;

/**
 * Converts a value to a numeric type (int or float) if possible.
 * Returns `false` if the value cannot be interpreted as a number.
 *
 * This is useful to safely normalize user input or data that might contain numeric strings.
 *
 * @param mixed           $value   The value to convert.
 * @param int|float|false $default The default value if the value is not a numeric value.
 *
 * @return float|int|false
 *
 * @example
 * ```php
 * use function oihana\core\toNumber;
 *
 * echo toNumber('42');           // 42
 * echo toNumber('3.14');         // 3.14
 * echo toNumber('-0.5e2');       // -50.0
 * echo toNumber('foo');          // false
 * echo toNumber('foo', 0);       // 0
 * echo toNumber(null, -1);       // -1
 * ```
 *
 * @package oihana\core
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function toNumber( mixed $value , int|float|false $default = false ) :float|int|false
{
    return is_numeric( $value ) ? $value + 0 : $default ;
}
