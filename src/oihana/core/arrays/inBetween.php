<?php

namespace oihana\core\arrays ;

/**
 * Insert an element between all the items in an array.
 *
 * @param array $source The source array
 * @param mixed $element The element to insert between items
 * @return array A new array with elements inserted between original items
 *
 * @example
 * ```php
 * print_r( inBetween(['a', 'b', 'c'], '/') ) ; // ["a", "/", "b", "/", "c"]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function inBetween( array $source , mixed $element ): array
{
    $result = [];
    $length = count($source);

    if ( $length <= 1 )
    {
        return $source;
    }

    foreach ( $source as $index => $value )
    {
        $result[] = $value;
        if ( $index < $length - 1 )
        {
            $result[] = $element;
        }
    }

    return $result;
}