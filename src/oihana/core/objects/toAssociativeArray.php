<?php

namespace oihana\core\objects ;

use function oihana\core\callables\resolveCallable;

/**
 * Recursively converts an object (or array) into a full associative array.
 *
 * This function handles nested objects, ensuring the entire array or object tree is converted.
 *
 * Note that only public properties of the object will be included in the resulting array.
 *
 * @param array|object $document An array or object to convert to a deep associative array .
 *
 * @param string|array|object|null $encoder Optional JSON encoder reference.
 * This value is resolved into a callable using {@see resolveCallable()}.
 * Supported forms:
 * - Closure or invokable object
 * - Callable array: [$object, 'method'] or ['Class', 'method']
 * - Named function: 'my_json_encoder'
 * - Static method string: 'MyClass::encode'
 * - null to use native json_encode()
 *
 * The resolved callable must have the signature: `function(mixed $data): string`
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
 * //      (
 * //          [street] => 123 PHP Avenue
 * //          [city] => Codeville
 * //      )
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
 * Convert using a custom JSON encoder (Closure):
 * ```php
 * $encoder = function (mixed $data): string
 * {
 *     // Example: pretty-print JSON and remove null values
 *     return json_encode($data, JSON_PRETTY_PRINT);
 * };
 *
 * $result = toAssociativeArray($userObject, $encoder);
 *
 * print_r($result);
 * ```
 *
 * Convert using a static serializer method:
 * ```php
 * use oihana\reflect\utils\JsonSerializer;
 * use oihana\core\options\ArrayOption;
 *
 * $result = toAssociativeArray
 * (
 * $userObject,
 *    fn(mixed $data): string =>
 *        JsonSerializer::encode( $data , jsonFlags: 0 , options: [ArrayOption::REDUCE => true] )
 * );
 *
 * print_r($result);
 * ```
 *
 * Convert using a named function or static method string:
 * ```php
 * // Using a named function
 * function my_json_encoder(mixed $data): string
 * {
 *     return json_encode($data, JSON_UNESCAPED_SLASHES);
 * }
 *
 * $result1 = toAssociativeArray($userObject, 'my_json_encoder');
 *
 * // Using a static method string
 * $result2 = toAssociativeArray($userObject, 'MyJsonHelper::encode');
 *
 * print_r($result1);
 * print_r($result2);
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function toAssociativeArray
(
    array|object             $document ,
    string|array|object|null $encoder  = null
)
:array
{
    $encoder = resolveCallable( $encoder ) ;

    $json = $encoder !== null ? $encoder( $document ) : json_encode( $document ) ;

    return json_decode( $json , true ) ;
}