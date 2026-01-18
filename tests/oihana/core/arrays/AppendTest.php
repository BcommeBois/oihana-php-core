<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

final class AppendTest extends TestCase
{
    public function testAppendAddsKeysAtTheEnd(): void
    {
        $array = [
            'name' => 'Alice',
        ];

        $after = [
            'timestamp' => 1234567890,
        ];

        $result = append( $array , $after );

        $this->assertSame(
            [
                'name'      => 'Alice',
                'timestamp' => 1234567890,
            ],
            $result
        );
    }

    public function testAppendDoesNotOverrideExistingKeys(): void
    {
        $array = [
            'name' => 'Alice',
        ];

        $after = [
            'name' => 'Bob',
            'age'  => 30,
        ];

        $result = append( $array , $after );

        $this->assertSame(
            [
                'name' => 'Alice',
                'age'  => 30,
            ],
            $result
        );
    }

    public function testAppendWithEmptyAfterReturnsOriginalArray(): void
    {
        $array = [
            'a' => 1,
            'b' => 2,
        ];

        $result = append( $array , [] );

        $this->assertSame( $array , $result );
    }

    public function testAppendWithEmptyArrayReturnsAfter(): void
    {
        $after = [
            'a' => 1,
            'b' => 2,
        ];

        $result = append( [] , $after );

        $this->assertSame( $after , $result );
    }

    public function testAppendPreservesNumericKeys(): void
    {
        $array = [
            0 => 'zero',
            1 => 'one',
        ];

        $after = [
            2 => 'two',
        ];

        $result = append( $array , $after );

        $this->assertSame(
            [
                0 => 'zero',
                1 => 'one',
                2 => 'two',
            ],
            $result
        );
    }

    public function testAppendIgnoresDuplicateNumericKeys(): void
    {
        $array = [
            0 => 'zero',
        ];

        $after = [
            0 => 'override',
            1 => 'one',
        ];

        $result = append( $array , $after );

        $this->assertSame(
            [
                0 => 'zero',
                1 => 'one',
            ],
            $result
        );
    }
}
