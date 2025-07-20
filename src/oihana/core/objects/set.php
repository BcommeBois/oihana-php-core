<?php

namespace oihana\core\objects;

use InvalidArgumentException;
use stdClass;

/**
 * Sets a value in an object using a key path.
 * Supports dot notation for nested properties. Intermediate objects are created if needed.
 *
 * @param object $object The object to modify (or copy).
 * @param string|null $key The key path to set (e.g. 'user.address.country'). If null, replaces entire object.
 * @param mixed $value The value to set.
 * @param string $separator The separator used in the key path. Default is '.'.
 * @param bool $copy If true, returns a deep copy of the object with the modification.
 * @param array|string|callable|null $classFactory A class name, factory callable, or array path => className to create intermediate objects (default: stdClass).
 *
 * @return object The modified (or copied and modified) object.
 *
 * @example
 * 1. Basic usage with nested keys and default stdClass
 * ```php
 * $obj = new \stdClass();
 * $obj = set($obj, 'user.profile.name', 'Alice');
 * echo $obj->user->profile->name; // Alice
 * ```
 *
 * 2. Replace the entire object when key is null
 * ```php
 * $original = (object)['foo' => 'bar'];
 * $new = set($original, null, ['x' => 123]);
 * echo $new->x; // 123
 * ```
 *
 * 3. Use a custom class as intermediate objects
 * ```php
 * class Node { public string $label = ''; }
 * $obj = set(new Node(), 'tree.branch.leaf', 'green', '.', false, Node::class);
 * echo $obj->tree->branch->leaf; // green
 * ```
 *
 * 4. Use a factory callable
 * ```php
 * $factory = fn() => new class { public string $value = ''; };
 * $obj = set(new \stdClass(), 'x.y.z', 99, '.', false, $factory);
 * echo $obj->x->y->z; // 99
 * ```
 *
 * 5. Use an array of path => class mappings
 * ```php
 * class Address { public string $city = ''; }
 * class User    { public string $name = ''; }
 * $obj = new \stdClass();
 * $obj = set($obj, 'user.address.city', 'Paris', '.', false, [
 * 'user' => User::class,
 * 'user.address' => Address::class,
 * ]);
 * echo $obj->user->address->city; // Paris
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function set
(
    object                     $object ,
    ?string                    $key ,
    mixed                      $value ,
    string                     $separator = '.' ,
    bool                       $copy = false ,
    array|string|callable|null $classFactory = null
)
: object
{
    if ( $copy ) {
        $object = unserialize( serialize( $object ) ) ; // deep clone
    }

    if ( $key === null ) {
        return (object) $value;
    }

    // Define default class creator
    $createInstance = match ( true )
    {
        is_array( $classFactory ) => function( string $path ) use ( $classFactory )
        {
            if ( isset( $classFactory[ $path ] ) )
            {
                $className = $classFactory[ $path ];
                return new $className();
            }
            return new stdClass() ;
        },
        is_callable ( $classFactory ) => $classFactory ,
        is_string   ( $classFactory ) => function() use ($classFactory)
        {
            if ( !class_exists($classFactory) )
            {
                throw new InvalidArgumentException("Class {$classFactory} does not exist");
            }
            return new $classFactory();
        },
        default => fn() => new stdClass() ,
    };

    $ref         = &$object;
    $keys        = explode( $separator , $key );
    $currentPath = '' ;

    while ( count( $keys ) > 1 )
    {
        $segment = array_shift( $keys );

        $currentPath = $currentPath === '' ? $segment : $currentPath . $separator . $segment ;

        if ( !property_exists( $ref , $segment ) || ! is_object( $ref->$segment ) )
        {
            $ref->$segment = is_array( $classFactory ) || is_callable($classFactory)
                           ? $createInstance( $currentPath )
                           : $createInstance() ;
        }

        $ref = &$ref->$segment;
    }

    $ref->{ array_shift( $keys ) } = $value;

    return $object;
}