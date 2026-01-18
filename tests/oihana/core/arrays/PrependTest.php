<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

final class PrependTest extends TestCase
{
    public function testPrependAddsKeysAtTheBeginning(): void
    {
        $array = [
            'name' => 'Alice',
        ];

        $before = [
            'id' => 'A',
        ];

        $result = prepend( $array , $before );

        $this->assertSame(
            [
                'id'   => 'A',
                'name' => 'Alice',
            ],
            $result
        );
    }

    public function testPrependOverridesArrayOrderButNotValues(): void
    {
        $array = [
            'id'   => 'B',
            'name' => 'Alice',
        ];

        $before = [
            'id' => 'A',
        ];

        $result = prepend( $array , $before );

        // value from $before is kept, value from $array is ignored
        $this->assertSame(
            [
                'id'   => 'A',
                'name' => 'Alice',
            ],
            $result
        );
    }

    public function testPrependWithEmptyBeforeReturnsOriginalArray(): void
    {
        $array = [
            'a' => 1,
            'b' => 2,
        ];

        $result = prepend( $array , [] );

        $this->assertSame( $array , $result );
    }

    public function testPrependWithEmptyArrayReturnsBefore(): void
    {
        $before = [
            'a' => 1,
            'b' => 2,
        ];

        $result = prepend( [] , $before );

        $this->assertSame( $before , $result );
    }

    public function testPrependPreservesNumericKeys(): void
    {
        $array = [
            1 => 'one',
            2 => 'two',
        ];

        $before = [
            0 => 'zero',
        ];

        $result = prepend( $array , $before );

        $this->assertSame(
            [
                0 => 'zero',
                1 => 'one',
                2 => 'two',
            ],
            $result
        );
    }

    public function testPrependIgnoresDuplicateNumericKeysFromArray(): void
    {
        $array = [
            0 => 'zero',
            1 => 'one',
        ];

        $before = [
            0 => 'override',
        ];

        $result = prepend( $array , $before );

        $this->assertSame(
            [
                0 => 'override',
                1 => 'one',
            ],
            $result
        );
    }
}
