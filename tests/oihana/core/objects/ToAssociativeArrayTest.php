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
}