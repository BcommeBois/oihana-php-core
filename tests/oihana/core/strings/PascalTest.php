<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\pascal;

use PHPUnit\Framework\TestCase;

class PascalTest extends TestCase
{
    public function testConvertsSnakeCase() : void
    {
        $this->assertSame( 'HelloWorld' , pascal( 'hello_world' ) ) ;
    }

    public function testConvertsMixedSeparators() : void
    {
        $this->assertSame( 'FooBarBaz' , pascal( 'foo-bar_baz' ) ) ;
    }

    public function testConvertsSlash() : void
    {
        $this->assertSame( 'UserName' , pascal( 'user/name' ) ) ;
    }

    public function testAlreadyCamelCase() : void
    {
        $this->assertSame( 'AlreadyCamel' , pascal( 'alreadyCamel' ) ) ;
    }

    public function testNullReturnsEmptyString() : void
    {
        $this->assertSame( '' , pascal( null ) ) ;
    }
}
