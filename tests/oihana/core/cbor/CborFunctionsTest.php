<?php

namespace tests\oihana\core\cbor;

use Beau\CborPHP\CborDecoder;
use CBOR\MapObject;
use JsonSerializable;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function oihana\core\cbor\cbor_encode;
use function oihana\core\cbor\cbor_decode;
use function oihana\core\cbor\cborToPhp;
use function oihana\core\cbor\phpToCbor;
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
        cbor_decode(chr(0x18));
    }

    public function testPhpToCbor(): void
    {
        $input = ['id' => 123, 'name' => 'Test'];

        $cborObject = phpToCbor($input);

        $this->assertInstanceOf( MapObject::class, $cborObject);

        $output = cborToPhp($cborObject);

        $this->assertSame($input, $output);
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

    public function testProductResponseDecoding(): void
    {
        $data = <<<'EOD'
fstatusgsuccesscurlx%http://10.57.57.11/products/200220674fresulte@typegProducth@contextxhttps://schema.bouney.frd_keyi200220674bidf105488dnamex#Abrasif granat stf d125/8 p120gr/50curlx%http://10.57.57.11/products/200220674gcreatedt2025-01-22T00:00:00Zhmodifiedt2026-01-26T14:44:57ZradditionalPropertye@typemPropertyValueh@contextrhttps://schema.orgjpropertyIDegrainevalue�e@typemPropertyValueh@contextrhttps://schema.orgjpropertyIDdkindevaluemabrasif - Lote@typemPropertyValueh@contextrhttps://schema.orgjpropertyIDgqualityevaluepD125/8 P120GR/50malternateNamegFestoolhcategorye@typekDefinedTermh@contextrhttps://schema.orgd_keyh64572935biddnamemLibre servicecurlx9http://10.57.57.11/thesaurus/products/categories/64572935e@typekDefinedTermh@contextrhttps://schema.orgd_keyh64573193biddnameiOutillagecurlx9http://10.57.57.11/thesaurus/products/categories/64573193e@typekDefinedTermh@contextrhttps://schema.orgd_keyh64572395bidŭdnamelConsommablescurlx9http://10.57.57.11/thesaurus/products/categories/64572395gdensityfheightjidentifiernFESTOOLABRASIFginStockninventoryLevele@typejStockLevelh@contextxhttps://schema.bouney.frkassignedPOSe@typeiWarehouseh@contextxhttps://schema.bouney.frd_keyi127799242bida1dnamefBouneycurlx'http://10.57.57.11/warehouses/127799242gownedByd_keyi127805669dnamefBouneycurlx)http://10.57.57.11/subsidiaries/127805669bidc501nlastStockEntryj2025-09-16mlastStockExitj2025-02-10hmaxValuehminValueevaluee@typejStockLevelh@contextxhttps://schema.bouney.frkassignedPOSe@typeiWarehouseh@contextxhttps://schema.bouney.frd_keyi127799245bidc101dnamejBeaumartincurlx'http://10.57.57.11/warehouses/127799245gownedByd_keyi127805681dnamejBeaumartincurlx)http://10.57.57.11/subsidiaries/127805681bidc502hmaxValuehminValueevaluee@typejStockLevelh@contextxhttps://schema.bouney.frkassignedPOSe@typeiWarehouseh@contextxhttps://schema.bouney.frd_keyi127799248bidc400dnameeRodezcurlx'http://10.57.57.11/warehouses/127799248gownedByd_keyi137186765dnamenAD MENUISERIEScurlx)http://10.57.57.11/subsidiaries/137186765bidc500hmaxValuehminValueevaluee@typejStockLevelh@contextxhttps://schema.bouney.frkassignedPOSe@typeiWarehouseh@contextxhttps://schema.bouney.frd_keyi127799251bidc401dnamegGaillaccurlx'http://10.57.57.11/warehouses/127799251gownedByd_keyi137186765dnamenAD MENUISERIEScurlx)http://10.57.57.11/subsidiaries/137186765bidc500hmaxValuehminValueevalueflengthmpriceCategorye@typekDefinedTermh@contextrhttps://schema.orgd_keyh64576450bidb06dnamexIsolation & étanchéitécurlx?http://10.57.57.11/thesaurus/products/price/categories/64576450e@typekDefinedTermh@contextrhttps://schema.orgd_keyh64576480bidd0605dnamenLaine de verrecurlx?http://10.57.57.11/thesaurus/products/price/categories/64576480kproductTypee@typekProductTypeh@contextxhttps://schema.bouney.frd_keyh64155460bidcNEGdnamexArticles dimensions fixescurlx4http://10.57.57.11/thesaurus/products/types/64155460hisPartOfd_keyh64155457dnamehArticlescurlx4http://10.57.57.11/thesaurus/products/types/64155457bidbARistockableitrackableistockableitrackablefsloganx,Festool abrasif GRANAT STF D125/8 P120 GR/50junitOfSalexhttps://schema.bouney.fr#Unitcvate@typegTaxRateh@contextxhttps://schema.bouney.frd_keyh64577860bida1curlx3http://10.57.57.11/thesaurus/products/vats/64577860kdescriptionmTaux TVA 20 %epricee20.00hunitCodecPC1hunitTextgPercentivalidFromj2014-01-01fvolumekwebCategorye@typekDefinedTermh@contextrhttps://schema.orgd_keyh64575536bida0dnameiHors sitecurlx=http://10.57.57.11/thesaurus/products/web/categories/64575536fweight@ewidth
EOD;

        $decoded = CborDecoder::decode($data);

        $this->assertIsArray($decoded, 'Le résultat décodé doit être un tableau');

        $this->assertArrayHasKey('status', $decoded, 'La clé "status" doit exister');
        $this->assertEquals('success', $decoded['status'], 'Le status doit être "success"');

        $this->assertArrayHasKey('url', $decoded, 'La clé "url" doit exister');
        $this->assertStringContainsString('products/200220674', $decoded['url']);

        $this->assertArrayHasKey('result', $decoded, 'La clé "result" doit exister');
        $result = $decoded['result'];
        $this->assertIsArray($result, '"result" doit être un tableau');

        // === TESTS DU PRODUCT (SCHEMA.ORG) ===
        $this->assertArrayHasKey('@type', $result);
        $this->assertEquals('Product', $result['@type']);

        $this->assertArrayHasKey('@context', $result);
        $this->assertEquals('https://schema.bouney.fr', $result['@context']);

        // === TESTS DES IDENTIFIANTS DU PRODUIT ===
        $this->assertArrayHasKey('_key', $result);
        $this->assertEquals('200220674', $result['_key']);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('105488', $result['id']);

        $this->assertArrayHasKey('name', $result);
        $this->assertEquals('Abrasif granat stf d125/8 p120gr/50', $result['name']);

        $this->assertArrayHasKey('identifier', $result);
        $this->assertEquals('FESTOOLABRASIF', $result['identifier']);

        // === TESTS DES URLS ===
        $this->assertArrayHasKey('url', $result);
        $this->assertStringContainsString('products/200220674', $result['url']);

        // === TESTS DES DATES ===
        $this->assertArrayHasKey('created', $result);
        $this->assertEquals('2025-01-22T00:00:00Z', $result['created']);

        $this->assertArrayHasKey('modified', $result);
        $this->assertStringStartsWith('2026-01-26', $result['modified']);

        // === TESTS DU STOCK ===
        $this->assertArrayHasKey('inStock', $result);
        $this->assertTrue($result['inStock'], 'Le produit doit être en stock');

        $this->assertArrayHasKey('stockable', $result);
        $this->assertTrue($result['stockable'], 'Le produit doit être stockable');

        $this->assertArrayHasKey('trackable', $result);
        $this->assertTrue($result['trackable'], 'Le produit doit être traçable');

        // === TESTS DES PROPRIÉTÉS ADDITIONNELLES ===
        $this->assertArrayHasKey('additionalProperty', $result);
        $this->assertIsArray($result['additionalProperty']);
        $this->assertNotEmpty($result['additionalProperty']);
        $this->assertCount(3, $result['additionalProperty'], 'Doit avoir 3 propriétés additionnelles');

        foreach ($result['additionalProperty'] as $index => $property) {
            $this->assertArrayHasKey('@type', $property, "Property $index doit avoir @type");
            $this->assertEquals('PropertyValue', $property['@type']);
            $this->assertArrayHasKey('propertyID', $property, "Property $index doit avoir propertyID");
            $this->assertArrayHasKey('value', $property, "Property $index doit avoir value");
        }

        // === TESTS DES CATÉGORIES ===
        $this->assertArrayHasKey('category', $result);
        $this->assertIsArray($result['category']);
        $this->assertNotEmpty($result['category']);
        $this->assertCount(3, $result['category'], 'Doit avoir 3 catégories');

        foreach ($result['category'] as $index => $category) {
            $this->assertArrayHasKey('@type', $category, "Category $index doit avoir @type");
            $this->assertEquals('DefinedTerm', $category['@type']);
            $this->assertArrayHasKey('name', $category, "Category $index doit avoir name");
            $this->assertArrayHasKey('_key', $category, "Category $index doit avoir _key");
        }

        // Vérifier des catégories spécifiques
        $categoryNames = array_column($result['category'], 'name');
        $this->assertContains('Libre service', $categoryNames);
        $this->assertContains('Outillage', $categoryNames);
        $this->assertContains('Consommables', $categoryNames);

        // === TESTS DES NIVEAUX D'INVENTAIRE ===
        $this->assertArrayHasKey('inventoryLevel', $result);
        $this->assertIsArray($result['inventoryLevel']);
        $this->assertNotEmpty($result['inventoryLevel']);
        $this->assertGreaterThanOrEqual(4, count($result['inventoryLevel']), 'Doit avoir au moins 4 entrepôts');

        foreach ($result['inventoryLevel'] as $index => $level) {
            $this->assertArrayHasKey('@type', $level, "InventoryLevel $index doit avoir @type");
            $this->assertEquals('StockLevel', $level['@type']);
            $this->assertArrayHasKey('assignedPOS', $level, "InventoryLevel $index doit avoir assignedPOS");

            $warehouse = $level['assignedPOS'];
            $this->assertArrayHasKey('@type', $warehouse);
            $this->assertEquals('Warehouse', $warehouse['@type']);
            $this->assertArrayHasKey('name', $warehouse);
            $this->assertArrayHasKey('ownedBy', $warehouse);
        }

        // Vérifier des entrepôts spécifiques
        $warehouseNames = array_map(fn($l) => $l['assignedPOS']['name'], $result['inventoryLevel']);
        $this->assertContains('Bouney', $warehouseNames);
        $this->assertContains('Beaumartin', $warehouseNames);

        // === TESTS DE LA TVA ===
        $this->assertArrayHasKey('vat', $result);
        $this->assertIsArray($result['vat']);

        $this->assertArrayHasKey('@type', $result['vat']);
        $this->assertEquals('TaxRate', $result['vat']['@type']);

        $this->assertArrayHasKey('price', $result['vat']);
        $this->assertEquals(20.00, $result['vat']['price']);

        $this->assertArrayHasKey('description', $result['vat']);
        $this->assertStringContainsString('20 %', $result['vat']['description']);

        $this->assertArrayHasKey('unitCode', $result['vat']);
        $this->assertEquals('PC1', $result['vat']['unitCode']);

        $this->assertArrayHasKey('unitText', $result['vat']);
        $this->assertEquals('Percent', $result['vat']['unitText']);

        // === TESTS DES INFORMATIONS MARKETING ===
        $this->assertArrayHasKey('alternateName', $result);
        $this->assertEquals('Festool', $result['alternateName']);

        $this->assertArrayHasKey('slogan', $result);
        $this->assertStringContainsString('Festool', $result['slogan']);
        $this->assertStringContainsString('GRANAT', $result['slogan']);
        $this->assertStringContainsString('P120 GR/50', $result['slogan']);

        // === TESTS DU TYPE DE PRODUIT ===
        $this->assertArrayHasKey('productType', $result);
        $this->assertIsArray($result['productType']);

        $this->assertArrayHasKey('@type', $result['productType']);
        $this->assertEquals('ProductType', $result['productType']['@type']);

        $this->assertArrayHasKey('name', $result['productType']);
        $this->assertEquals('Articles dimensions fixes', $result['productType']['name']);

        $this->assertArrayHasKey('isPartOf', $result['productType']);
        $this->assertArrayHasKey('name', $result['productType']['isPartOf']);

        // === TESTS DES CATÉGORIES DE PRIX ===
        $this->assertArrayHasKey('priceCategory', $result);
        $this->assertIsArray($result['priceCategory']);
        $this->assertNotEmpty($result['priceCategory']);

        foreach ($result['priceCategory'] as $index => $priceCategory) {
            $this->assertArrayHasKey('@type', $priceCategory, "PriceCategory $index doit avoir @type");
            $this->assertEquals('DefinedTerm', $priceCategory['@type']);
            $this->assertArrayHasKey('name', $priceCategory);
        }

        // === TESTS DE LA CATÉGORIE WEB ===
        $this->assertArrayHasKey('webCategory', $result);
        $this->assertIsArray($result['webCategory']);

        $this->assertArrayHasKey('@type', $result['webCategory']);
        $this->assertEquals('DefinedTerm', $result['webCategory']['@type']);

        $this->assertArrayHasKey('name', $result['webCategory']);
        $this->assertEquals('Hors site', $result['webCategory']['name']);

        // === TESTS DES DIMENSIONS (peuvent être null) ===
        $dimensionKeys = ['density', 'height', 'length', 'volume', 'width', 'weight'];
        foreach ($dimensionKeys as $key) {
            $this->assertArrayHasKey($key, $result, "La clé '$key' doit exister");
        }

        // === TESTS DE L'UNITÉ DE VENTE ===
        $this->assertArrayHasKey('unitOfSale', $result);
        $this->assertStringContainsString('https://schema.bouney.fr', $result['unitOfSale']);
    }
}