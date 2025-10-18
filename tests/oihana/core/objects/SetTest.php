<?php

namespace oihana\core\objects ;

use stdClass;

use PHPUnit\Framework\TestCase;

use tests\oihana\core\mocks\Address;
use tests\oihana\core\mocks\GeoCoordinates;
use tests\oihana\core\mocks\User;

class SetTest extends TestCase
{
    public function testSetSimpleValue():void
    {
        $obj = new stdClass();
        set($obj, 'foo', 'bar');

        $this->assertTrue(property_exists($obj, 'foo'));
        $this->assertSame('bar', $obj->foo);
    }

    public function testSetNestedValueWithDefaultStdClass():void
    {
        $obj = new stdClass();
        set($obj, 'foo.bar.baz', 42);

        $this->assertSame(42, $obj->foo->bar->baz);
        $this->assertInstanceOf(stdClass::class, $obj->foo);
        $this->assertInstanceOf(stdClass::class, $obj->foo->bar);
    }

    public function testSetValueWithCustomClass():void
    {
        $obj = new User();

        set( $obj, 'address.country', 'France', '.', false, Address::class ) ;

        // $this->assertInstanceOf(Address::class , $obj->address );
        $this->assertSame('France', $obj->address->country);
    }

    public function testSetWithPathBasedFactory(): void
    {
        $factory = function( string $path ): object
        {
            return match( $path )
            {
                'user'             => new User() ,
                'user.address'     => new Address() ,
                'user.address.geo' => new GeoCoordinates(),
                default => new stdClass()
            };
        };

        $obj = new stdClass();

        set( $obj, 'user.address.geo.longitude', 2.3522, '.', false, $factory );

        // $this->assertInstanceOf(User::class, $obj->user);
        // $this->assertInstanceOf(Address::class, $obj->user->address);
        // $this->assertInstanceOf(GeoCoordinates::class, $obj->user->address->geo);
        $this->assertSame(2.3522, $obj->user->address->geo->longitude);
    }

    public function testSetNullKeyReplacesEntireObject():void
    {
        $obj = (object)['a' => 1];
        $result = set($obj, null, ['x' => 42]);

        $this->assertTrue(property_exists($result, 'x'));
        $this->assertSame(42, $result->x);
    }

    public function testCopyDoesNotModifyOriginal():void
    {
        $original = (object)['user' => (object)['name' => 'Marc']];
        $copy = set($original, 'user.name', 'Jean', '.', true);

        $this->assertSame('Marc', $original->user->name); // unchanged
        $this->assertSame('Jean', $copy->user->name);     // updated
    }

    public function testSetWithComplexPath(): void
    {
        $classMap =
        [
            'users' => stdClass::class,
            'users.123' => User::class,
            'users.123.address' => Address::class,
            'users.123.address.geo' => GeoCoordinates::class
        ];

        $obj = new stdClass();
        set($obj, 'users.123.address.geo.elevation', 35, '.', false, $classMap);

        $this->assertInstanceOf(User::class, $obj->users->{'123'});
        $this->assertInstanceOf(Address::class, $obj->users->{'123'}->address);
        $this->assertInstanceOf(GeoCoordinates::class, $obj->users->{'123'}->address->geo);
        $this->assertSame(35, $obj->users->{'123'}->address->geo->elevation);
    }
}