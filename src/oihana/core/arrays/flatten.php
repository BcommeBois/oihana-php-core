<?php

namespace oihana\core\arrays ;

/**
 * Flattens a nested array into a single-level array.
 * @param array $array The array to flatten.
 * @return array The flattened array.
 */
function flatten( array $array ): array
{
    $result = [];

    foreach ($array as $item)
    {
        if ( is_array( $item ) )
        {
            $result = array_merge( $result , flatten( $item ) );
        }
        else
        {
            $result[] = $item ;
        }
    }

    return $result;
}