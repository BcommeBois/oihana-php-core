<?php

namespace tests\oihana\core\cbor;

use JsonSerializable;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function oihana\core\cbor\cbor_encode;
use function oihana\core\cbor\cbor_decode;
use function oihana\core\objects\toAssociativeArray;

class CborFunctionsTest extends TestCase
{
    public function testRoundTripScalars(): void
    {
        $input = "Hello CBOR";
        $encoded = cbor_encode($input);

        $this->assertIsString($encoded);
        $this->assertSame($input, cbor_decode($encoded));

        $this->assertSame(42, cbor_decode(cbor_encode(42)));
        $this->assertTrue(cbor_decode(cbor_encode(true)));
        $this->assertNull(cbor_decode(cbor_encode(null)));
    }

    public function testRoundTripList(): void
    {
        $input = ['id' , 'active' ];
        $this->assertSame($input, cbor_decode(cbor_encode($input)));
    }

    public function testRoundTripArrays(): void
    {
        $input = ['id' => 123, 'active' => true];
        $this->assertSame($input, cbor_decode(cbor_encode($input)));
    }

    public function testObjectNormalization(): void
    {
        $object = new class
        {
            public string $name = "Oihana";
            private string $secret = "Hidden";
        };

        $decoded = cbor_decode(cbor_encode($object));

        $this->assertIsArray($decoded);
        $this->assertEquals('Oihana', $decoded['name']);
        $this->assertArrayNotHasKey('secret', $decoded);
    }

    public function testDecodeInvalidDataThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        cbor_decode("\xFF"); // "break" code en CBOR invalide
    }

    public function testComplexeAssociativeArray(): void
    {
        $object = new class implements JsonSerializable
        {
            public string $name = "Oihana";
            private string $secret = "Hidden";

            public function jsonSerialize() : mixed
            {
                return
                [
                    "name"   => $this->name ,
                    "secret" => $this->secret ,
                    "array"  => [ "foo" => "bar" ]
                ];
            }
        };

        $input =
        [
            "status" => "success",
            "url" => "http://mydomain.tdl/products?skin=full",
            "count" => 50,
            "total" => 11793,
            "object" => $object ,
            "result" => [
                [
                    "@type" => "Product",
                    "@context" => "https://schema.oihana.xyz",
                    "_key" => "200220674",
                    "id" => "105488",
                    "name" => "XXXX XXXX XXXX",
                    "url" => "http://mydomain.tdl/products/200220674",
                    "created" => "2025-01-22T00:00:00Z",
                    "modified" => "2026-01-26T14:44:57Z",
                    "additionalProperty" => [
                        [
                            "@type" => "PropertyValue" ,
                            "@context" => "https://schema.org" ,
                            "propertyID" => "grain" ,
                            "value" => false
                        ] ,
                        [
                            "@type" => "PropertyValue" ,
                            "@context" => "https://schema.org" ,
                            "propertyID" => "kind" ,
                            "value" => "abrasif - Lot"
                        ] ,
                        [
                            "@type" => "PropertyValue" ,
                            "@context" => "https://schema.org" ,
                            "propertyID" => "quality" ,
                            "value" => "D125/8 P120GR/50"
                        ]
                    ],
                    "alternateName" => "Festool",
                    "category" => [
                        [
                            "@type" => "DefinedTerm" ,
                            "@context" => "https://schema.org" ,
                            "_key" => "64572935" ,
                            "id" => 5 ,
                            "name" => "Libre service" ,
                            "url" => "http://mydomain.tdl/thesaurus/products/categories/64572935"
                        ] ,
                        [
                            "@type" => "DefinedTerm" ,
                            "@context" => "https://schema.org" ,
                            "_key" => "64573193" ,
                            "id" => 506 ,
                            "name" => "Outillage" ,
                            "url" => "http://mydomain.tdl/thesaurus/products/categories/64573193"
                        ] ,
                        [
                            "@type" => "DefinedTerm" ,
                            "@context" => "https://schema.org" ,
                            "_key" => "64572395" ,
                            "id" => 50605 ,
                            "name" => "Consommables" ,
                            "url" => "http://mydomain.tdl/thesaurus/products/categories/64572395"
                        ]
                    ],
                    "density" => 0,
                    "height" => 0,
                    "identifier" => "xxxxxxx",
                    "inStock" => true,
                    "inventoryLevel" => [
                        [
                            "@type" => "StockLevel" ,
                            "@context" => "https://schema.oihana.xyz" ,
                            "assignedPOS" => [
                                "@type" => "Warehouse" ,
                                "@context" => "https://schema.oihana.xyz" ,
                                "_key" => "127799242" ,
                                "id" => "1" ,
                                "name" => "XXXX" ,
                                "url" => "http://mydomain.tdl/warehouses/127799242" ,
                                "ownedBy" => [
                                    "_key" => "127805669" ,
                                    "name" => "XXXX" ,
                                    "url" => "http://mydomain.tdl/subsidiaries/127805669" ,
                                    "id" => "501"
                                ]
                            ] ,
                            "lastStockEntry" => "2025-09-16" ,
                            "lastStockExit" => "2025-02-10" ,
                            "maxValue" => 0 ,
                            "minValue" => 0 ,
                            "value" => 0
                        ] ,
                        [
                            "@type" => "StockLevel" ,
                            "@context" => "https://schema.oihana.xyz" ,
                            "assignedPOS" => [
                                "@type" => "Warehouse" ,
                                "@context" => "https://schema.oihana.xyz" ,
                                "_key" => "127799245" ,
                                "id" => "101" ,
                                "name" => "XXXX" ,
                                "url" => "http://mydomain.tdl/warehouses/127799245" ,
                                "ownedBy" => [
                                    "_key" => "127805681" ,
                                    "name" => "XXXX" ,
                                    "url" => "http://mydomain.tdl/subsidiaries/127805681" ,
                                    "id" => "502"
                                ]
                            ] ,
                            "maxValue" => 2 ,
                            "minValue" => 1 ,
                            "value" => 0
                        ] ,
                        [
                            "@type" => "StockLevel" ,
                            "@context" => "https://schema.oihana.xyz" ,
                            "assignedPOS" => [
                                "@type" => "Warehouse" ,
                                "@context" => "https://schema.oihana.xyz" ,
                                "_key" => "127799248" ,
                                "id" => "400" ,
                                "name" => "XXXX" ,
                                "url" => "http://mydomain.tdl/warehouses/127799248" ,
                                "ownedBy" => [
                                    "_key" => "137186765" ,
                                    "name" => "XXXX" ,
                                    "url" => "http://mydomain.tdl/subsidiaries/137186765" ,
                                    "id" => "500"
                                ]
                            ] ,
                            "maxValue" => 0 ,
                            "minValue" => 0 ,
                            "value" => 0
                        ] ,
                        [
                            "@type" => "StockLevel" ,
                            "@context" => "https://schema.oihana.xyz" ,
                            "assignedPOS" => [
                                "@type" => "Warehouse" ,
                                "@context" => "https://schema.oihana.xyz" ,
                                "_key" => "127799251" ,
                                "id" => "401" ,
                                "name" => "XXXX" ,
                                "url" => "http://mydomain.tdl/warehouses/127799251" ,
                                "ownedBy" => [
                                    "_key" => "137186765" ,
                                    "name" => "XXXX" ,
                                    "url" => "http://mydomain.tdl/subsidiaries/137186765" ,
                                    "id" => "500"
                                ]
                            ] ,
                            "maxValue" => 0 ,
                            "minValue" => 0 ,
                            "value" => 0
                        ]
                    ],
                    "length" => 0,
                    "priceCategory" => [
                        [
                            "@type" => "DefinedTerm" ,
                            "@context" => "https://schema.org" ,
                            "_key" => "64576450" ,
                            "id" => "06" ,
                            "name" => "Isolation & étanchéité" ,
                            "url" => "http://mydomain.tdl/thesaurus/products/price/categories/64576450"
                        ] ,
                        [
                            "@type" => "DefinedTerm" ,
                            "@context" => "https://schema.org" ,
                            "_key" => "64576480" ,
                            "id" => "0605" ,
                            "name" => "Laine de verre" ,
                            "url" => "http://mydomain.tdl/thesaurus/products/price/categories/64576480"
                        ]
                    ],
                ]
            ]
        ];

        // echo json_encode
        //(
        //    toAssociativeArray( $input , strict:true ) ,
        //    JSON_PRETTY_PRINT
        // ) . PHP_EOL . PHP_EOL  ;

        $this->assertSame
        (
            toAssociativeArray( $input , strict:true ) ,
            cbor_decode( cbor_encode( $input ) )
        ) ;
    }

    /**
     * Test that CBOR integers are properly converted to PHP integers
     * without being treated as strings, and that large integers are preserved.
     */
    public function testIntegerTypePreservation(): void
    {
        // Test standard integers
        $smallInt = 42;
        $negativeInt = -123;
        $zero = 0;

        $decodedSmall = cbor_decode(cbor_encode($smallInt));
        $decodedNegative = cbor_decode(cbor_encode($negativeInt));
        $decodedZero = cbor_decode(cbor_encode($zero));

        $this->assertIsInt($decodedSmall, 'Small positive integer should be int');
        $this->assertSame($smallInt, $decodedSmall);

        $this->assertIsInt($decodedNegative, 'Negative integer should be int');
        $this->assertSame($negativeInt, $decodedNegative);

        $this->assertIsInt($decodedZero, 'Zero should be int');
        $this->assertSame($zero, $decodedZero);
    }

    /**
     * Test that numeric strings remain as strings and are not coerced to integers
     */
    public function testNumericStringFidelity(): void
    {
        $numericString = "12345";
        $decoded = cbor_decode(cbor_encode($numericString));

        $this->assertIsString($decoded, 'Numeric string should remain a string');
        $this->assertSame($numericString, $decoded);
    }

    /**
     * Test that large integers beyond PHP_INT_MAX are preserved as strings
     * to prevent precision loss
     */
    public function testLargeIntegerPreservation(): void
    {
        // Create a number larger than PHP_INT_MAX
        $largeNumber = (string)(PHP_INT_MAX + 1000);

        $encoded  = cbor_encode($largeNumber);
        $decoded  = cbor_decode($encoded);

        // Should be preserved as string to avoid overflow
        $this->assertIsString($decoded, 'Large integer should be preserved as string');
        $this->assertSame($largeNumber, $decoded);
    }

    /**
     * Test integer types in nested structures
     */
    public function testIntegerTypesInNestedStructures(): void
    {
        $input = [
            'count' => 50,
            'total' => 11793,
            'id' => '105488', // This is a string in the original data
            'nested' => [
                'value' => 0,
                'maxValue' => 2,
                'minValue' => 1
            ]
        ];

        $decoded = cbor_decode(cbor_encode($input));

        // Integers should remain integers
        $this->assertIsInt($decoded['count'], 'count should be int');
        $this->assertSame(50, $decoded['count']);

        $this->assertIsInt($decoded['total'], 'total should be int');
        $this->assertSame(11793, $decoded['total']);

        // String IDs should remain strings
        $this->assertIsString($decoded['id'], 'id should remain string');
        $this->assertSame('105488', $decoded['id']);

        // Nested integers
        $this->assertIsInt($decoded['nested']['value']);
        $this->assertSame(0, $decoded['nested']['value']);

        $this->assertIsInt($decoded['nested']['maxValue']);
        $this->assertSame(2, $decoded['nested']['maxValue']);
    }

    /**
     * Test that ID fields with numeric values are correctly typed
     * based on their original PHP type (string vs int)
     */
    public function testIdFieldTypeConsistency(): void
    {
        $data = [
            'numeric_id' => 123,      // Should be int
            'string_id' => '123',     // Should be string
            'mixed_id' => 'ABC123'    // Should be string
        ];

        $decoded = cbor_decode(cbor_encode($data));

        $this->assertIsInt($decoded['numeric_id']);
        $this->assertSame(123, $decoded['numeric_id']);

        $this->assertIsString($decoded['string_id']);
        $this->assertSame('123', $decoded['string_id']);

        $this->assertIsString($decoded['mixed_id']);
        $this->assertSame('ABC123', $decoded['mixed_id']);
    }
}