<?php

namespace oihana\core\objects ;

/**
 * Sets a value in a flat object using a single property name.
 *
 * This helper function assigns the given value to the specified property
 * of the provided object. It does not support nested paths or separators.
 *
 * The object is returned with the updated property.
 *
 * @param object $document The object to modify.
 * @param string $key      The property name to set.
 * @param mixed  $value    The value to assign to the property.
 *
 * @return object The modified object with the new or updated property.
 *
 * @example
 * ```php
 * $obj = (object)['name' => 'Alice'];
 * $updated = setObjectValue($obj, 'age', 30);
 * // $updated = (object)['name' => 'Alice', 'age' => 30];
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function setObjectValue( object $document , string $key , mixed $value ) :object
{
    $document->{ $key } = $value ;
    return $document ;
}