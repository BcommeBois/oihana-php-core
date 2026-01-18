<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

final class PickTest extends TestCase
{
    public function testPickKeepsOnlySpecifiedKeys(): void
    {
        $array = [
            'id'    => 42,
            'name'  => 'Alice',
            'email' => 'alice@example.com',
            'role'  => 'admin',
        ];

        $result = pick( $array , [ 'id' , 'name' ] );

        $this->assertSame(
            [
                'id'   => 42,
                'name' => 'Alice',
            ],
            $result
        );
    }

    public function testPickIgnoresUnknownKeys(): void
    {
        $array = [
            'a' => 1,
            'b' => 2,
        ];

        $result = pick( $array , [ 'a' , 'c' ] );

        $this->assertSame(
            [
                'a' => 1,
            ],
            $result
        );
    }

    public function testPickWithEmptyKeysReturnsEmptyArray(): void
    {
        $array = [
            'a' => 1,
            'b' => 2,
        ];

        $result = pick( $array , [] );

        $this->assertSame( [] , $result );
    }

    public function testPickWithEmptyArrayReturnsEmptyArray(): void
    {
        $result = pick( [] , [ 'a' , 'b' ] );

        $this->assertSame( [] , $result );
    }

    public function testPickPreservesOriginalKeysAndValues(): void
    {
        $array = [
            'x' => null,
            'y' => false,
            'z' => 0,
        ];

        $result = pick( $array , [ 'x' , 'z' ] );

        $this->assertArrayHasKey( 'x' , $result );
        $this->assertArrayHasKey( 'z' , $result );
        $this->assertSame( null , $result['x'] );
        $this->assertSame( 0 , $result['z'] );
    }

    public function testPickWorksWithNumericKeys(): void
    {
        $array = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
        ];

        $result = pick( $array , [ 0 , 2 ] );

        $this->assertSame(
            [
                0 => 'zero',
                2 => 'two',
            ],
            $result
        );
    }
}
