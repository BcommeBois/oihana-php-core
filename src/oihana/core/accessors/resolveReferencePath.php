<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use stdClass;

use function oihana\core\arrays\ensureArrayPath;
use function oihana\core\objects\ensureObjectPath;

/**
 * Navigates to the parent container of the last key segment in a nested structure.
 *
 * This function traverses through nested arrays or objects based on the provided key path,
 * ensuring that each intermediate level exists. It returns a reference to the parent
 * container that should contain the final key segment.
 *
 * It is typically used to prepare for read/write operations on nested values.
 *
 * Requires external helpers:
 * - `ensureArrayPath()` (creates intermediate arrays)
 * - `ensureObjectPath()` (creates intermediate objects)
 *
 * @param array|object &$document The root document to navigate through (passed by reference).
 * @param array $keys The exploded path as array segments (e.g. ['user', 'profile', 'name']).
 * @param bool $isArray If true, treat the structure as arrays; if false, as objects.
 *
 * @return mixed Reference to the container (array or object) that should hold the final key.
 *
 * @example
 * ```php
 * $doc = [];
 * $keys = ['user', 'name'];
 * $ref = &resolveReferencePath($doc, $keys, true);
 * $ref['name'] = 'John';
 * // $doc is now: ['user' => ['name' => 'John']]
 * ```
 *
 * @example
 * ```php
 * $doc = new stdClass();
 * $keys = ['config', 'theme'];
 * $ref = &resolveReferencePath($doc, $keys, false);
 * $ref->theme = 'dark';
 * // $doc is now: (object)['config' => (object)['theme' => 'dark']]
 * ```
 *
 * @example
 * Mixed structure support:
 * ```php
 * $doc = ['data' => (object)['name' => 'Alice']];
 * $keys = ['data', 'age'];
 * $ref = &resolveReferencePath($doc, $keys, true);
 * $ref->age = 30;
 * // $doc is now: ['data' => (object)['name' => 'Alice', 'age' => 30]]
 * ```
 *
 * @example
 * Overwriting scalars:
 * ```php
 * $doc = ['user' => 'not_an_array'];
 * $keys = ['user', 'profile', 'name'];
 * $ref = &resolveReferencePath($doc, $keys, true);
 * $ref['name'] = 'Alice';
 * // $doc is now: ['user' => ['profile' => ['name' => 'Alice']]]
 * ```
 *
 * @see ensureArrayPath()
 * @see ensureObjectPath()
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function &resolveReferencePath
(
    array|object &$document,
    array        $keys ,
    bool         $isArray
)
: mixed
{
    $current = &$document;
    $length  = count( $keys ) - 1; // Navigate to parent of last key

    for ( $i = 0 ; $i < $length ; $i++ )
    {
        $segment = $keys[ $i ] ;
        if ( is_array( $current ) )
        {
            if (!isset( $current[$segment] ) || ( !is_array($current[$segment]) && !is_object( $current[$segment] ) ) )
            {
                $current[$segment] = $isArray ? [] : new stdClass() ;
            }
            $current = &$current[ $segment ] ;
        }
        elseif ( is_object( $current ) )
        {
            // Ensure property exists and is a container (array or object)
            if (!isset($current->{$segment}) || (!is_array($current->{$segment}) && !is_object($current->{$segment})))
            {
                $current->{$segment} = $isArray ? [] : new stdClass() ;
            }
            $current = &$current->{$segment};
        }
    }

    return $current;
}