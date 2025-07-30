<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class WrapBlockTest extends TestCase
{
    public function testWrapBlockWithArrayLines(): void
    {
        $lines = ['line1', '', 'line2'];

        $expected = "{\n    line1\n    \n    line2\n}";

        $this->assertSame($expected, wrapBlock($lines, '{', '}', 4));
    }

    public function testWrapBlockWithoutEmptyLines(): void
    {
        $lines = ['line1', '', 'line2'];

        $expected = "{\n    line1\n    line2\n}";

        $this->assertSame($expected, wrapBlock($lines, '{', '}', 4, PHP_EOL, false));
    }
}