<?php

namespace oihana\core\objects ;

/**
 * Recursively converts an object (or array) into a full associative array.
 *
 * This function handles nested objects, ensuring the entire array or object tree is converted.
 *
 * Note that only public properties of the object will be included in the resulting array.
 *
 * @param array|object $document An array or object to convert to a deep associative array .
 *
 * @return array The resulting associative array.
 *
 * @example
 * Convert an object :
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
 * Convert an array with sub-objects:
 * ```php
 * $data = (object)
 * [
 *     'id' => 123,
 *     'name' => 'Project Alpha',
 *     'provider' => (object)
 *     [
 *        'name' => 'Alice',
 *        'role' => 'Chef de projet'
 *     ],
 *     'team' =>
 *      [
 *         (object) ['name' => 'Bob'     ] ,
 *         (object) ['name' => 'Charlie' ]
 *      ]
 * ];
 *
 * $arrayAssoc = toAssociativeArray($data);
 *
 * print_r($arrayAssoc);
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function toAssociativeArray( array|object $document ) :array
{
    return json_decode( json_encode( $document ) , true ) ;
}