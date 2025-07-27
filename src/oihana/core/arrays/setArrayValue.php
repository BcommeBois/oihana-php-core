<?php

namespace oihana\core\arrays ;

/**
 * Sets a value in a flat associative array using a single key.
 *
 * This helper function assigns the given value to the specified key
 * in the provided array. It does not support nested keys or separators.
 *
 * The array is returned with the updated value, leaving the original array
 * unmodified if passed by value.
 *
 * @param array  $document The associative array to modify.
 * @param string $key      The key at which to set the value.
 * @param mixed  $value    The value to assign to the key.
 *
 * @return array The modified array with the new or updated key/value pair.
 *
 * @example
 * ```php
 * $data = ['name' => 'Alice'];
 * $updated = setArrayValue($data, 'age', 30);
 * // $updated = ['name' => 'Alice', 'age' => 30];
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function setArrayValue( array $document , string $key, mixed $value): array
{
    $document[ $key ] = $value;
    return $document;
}