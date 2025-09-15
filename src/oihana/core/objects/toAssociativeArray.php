<?php

namespace oihana\core\objects ;

/**
 * Recursively converts an object into an associative array.
 *
 * This function handles nested objects, ensuring the entire object tree is converted.
 * It achieves this by first encoding the object to a JSON string and then decoding it
 * back into an associative array. Note that only public properties of the object
 * will be included in the resulting array.
 *
 * @param object $document The object to convert.
 *
 * @return array The resulting associative array.
 *
 * @example
 * ```php
 * // Define some classes for the example.
 * class Address
 * {
 *     public string $street = '123 PHP Avenue';
 *     public string $city = 'Codeville';
 * }
 *
 * class User
 * {
 *     public int $id = 42;
 *     public string $name = 'John Doe';
 *     public Address $address;
 *     private string $sessionToken = 'a-very-secret-token'; // This will be ignored.
 *
 *     public function __construct()
 *     {
 *         $this->address = new Address();
 *     }
 * }
 *
 * $userObject = new User();
 *
 * $userArray = toAssociativeArray($userObject);
 *
 * print_r($userArray);
 * // Output:
 * // Array
 * // (
 * //     [id] => 42
 * //     [name] => John Doe
 * //     [address] => Array
 * //         (
 * //             [street] => 123 PHP Avenue
 * //             [city] => Codeville
 * //         )
 * // )
 * // Note that the private property 'sessionToken' is not present.
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function toAssociativeArray( object $document ) :array
{
    return json_decode( json_encode( $document ) , true ) ;
}