<?php

namespace tests\oihana\core\objects ;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use function oihana\core\objects\toAssociativeArray;
use stdClass;

class TestAddress
{
    public string $street = '123 PHP Avenue';
    public string $city = 'Codeville';
}

class TestUser
{
    public    int          $id           = 42;
    public    string       $name         = 'John Doe';
    public    ?TestAddress $address      = null;
    protected string       $role         = 'user';
    private   string       $sessionToken = 'secret-token';

    public function __construct(bool $withAddress = false)
    {
        if ( $withAddress )
        {
            $this->address = new TestAddress();
        }
    }
}

// A simple static encoder helper for tests.
class TestJsonEncoder
{
    public static function encode(mixed $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}

// --- La classe de test PHPUnit mise à jour ---

#[CoversFunction('oihana\core\objects\toAssociativeArray')]
class ToAssociativeArrayTest extends TestCase
{
    #[Test]
    public function it_converts_a_simple_object_with_public_properties(): void
    {
        $object = new TestUser();
        $result = toAssociativeArray($object);
        $expected =
        [
            'id' => 42,
            'name' => 'John Doe',
            'address' => null,
        ];
        $this->assertEquals($expected, $result);
    }

    #[Test]
    public function it_recursively_converts_nested_objects(): void
    {
        $object   = new TestUser(withAddress: true);
        $result   = toAssociativeArray($object);
        $expected =
        [
            'id' => 42,
            'name' => 'John Doe',
            'address' => [
                'street' => '123 PHP Avenue',
                'city' => 'Codeville',
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    #[Test]
    public function it_ignores_private_and_protected_properties(): void
    {
        $object = new TestUser();

        $result = toAssociativeArray($object);

        $this->assertArrayNotHasKey('role', $result);
        $this->assertArrayNotHasKey('sessionToken', $result);
    }

    #[Test]
    public function it_handles_an_empty_object(): void
    {
        $emptyObject = new stdClass();

        $result = toAssociativeArray($emptyObject);

        $this->assertEmpty($result);
        $this->assertEquals([], $result);
    }

    #[Test]
    public function it_uses_a_closure_encoder(): void
    {
        $object = new TestUser(withAddress: true);

        $encoder = function (mixed $data): string
        {
            return json_encode($data, JSON_UNESCAPED_SLASHES);
        };

        $result = toAssociativeArray($object, $encoder);

        $this->assertEquals(42, $result['id']);
        $this->assertEquals('John Doe', $result['name']);
        $this->assertEquals('123 PHP Avenue', $result['address']['street']);
    }

    #[Test]
    public function it_uses_a_static_method_string_encoder(): void
    {
        $object = new TestUser(withAddress: true);

        $result = toAssociativeArray($object, TestJsonEncoder::class . '::encode');

        $this->assertEquals(42, $result['id']);
        $this->assertEquals('John Doe', $result['name']);
        $this->assertEquals('Codeville', $result['address']['city']);
    }

    #[Test]
    public function it_uses_a_callable_array_encoder(): void
    {
        $object = new TestUser(withAddress: true);

        $result = toAssociativeArray($object, [TestJsonEncoder::class, 'encode']);

        $this->assertEquals(42, $result['id']);
        $this->assertEquals('John Doe', $result['name']);
        $this->assertEquals('123 PHP Avenue', $result['address']['street']);
    }

    #[Test]
    public function it_falls_back_to_native_json_encode_when_encoder_is_null(): void
    {
        $object = new TestUser(withAddress: true);

        $result = toAssociativeArray($object);

        $expected =
            [
                'id'      => 42,
                'name'    => 'John Doe',
                'address' => [
                    'street' => '123 PHP Avenue',
                    'city'   => 'Codeville',
                ],
            ];

        $this->assertEquals($expected, $result);
    }
}