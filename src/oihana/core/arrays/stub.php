<?php

namespace oihana\core\arrays ;

/**
 * Returns a new empty array.
 *
 * This function is useful as a placeholder or default value generator
 * when an empty array is needed.
 *
 * @return array An empty array.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\stub;
 *
 * $emptyArray = stub();
 * var_dump($emptyArray); // outputs: array(0) {}
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function stub() :array
{
    return [];
}