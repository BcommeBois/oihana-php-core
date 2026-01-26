<?php

namespace tests\oihana\core\cbor;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

use function oihana\core\cbor\cbor_encode;
use function oihana\core\cbor\cbor_decode;

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

    public function testEncodeWithHelper(): void
    {
        $object = new stdClass();
        $object->key = "value";

        $helper = fn($d) => json_encode(['KEY' => 'UPPER']);

        $decoded = cbor_decode(cbor_encode($object, $helper));

        $this->assertSame(['KEY' => 'UPPER'], $decoded);
    }

    public function testDecodeInvalidDataThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        cbor_decode(chr(0x18));
    }
}