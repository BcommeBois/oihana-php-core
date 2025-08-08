<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

final class SliceTest extends TestCase
{
    public function testSliceBasic(): void
    {
        $this->assertSame('World', slice('Hello World', 6));
        $this->assertSame('Hello', slice('Hello World', 0, 5));
        $this->assertSame('lo Wo', slice('Hello World', 3, 5));
    }

    public function testSliceWithNullSource(): void
    {
        $this->assertSame('', slice(null));
        $this->assertSame('', slice(null, 0, 10));
    }

    public function testSliceNegativeStart(): void
    {
        $this->assertSame('rld', slice('Hello World', -3));
        $this->assertSame('rl', slice('Hello World', -3, 2));
    }

    public function testSliceMultibyteCharacters(): void
    {
        $str = "👩‍👩‍👧‍👧 family";

        // Full emoji family is one grapheme cluster, length=1 in grapheme terms
        $this->assertSame('👩‍👩‍👧‍👧', slice($str, 0, 1));
        $this->assertSame('👩‍👩‍👧‍👧 ', slice($str, 0, 2));
        $this->assertSame('family', slice($str, 2));
    }

    public function testSliceLengthLargerThanString(): void
    {
        $this->assertSame('Hello World', slice('Hello World', 0, 1000));
    }

    public function testSliceZeroLength(): void
    {
        $this->assertSame('', slice('Hello World', 0, 0));
    }
}