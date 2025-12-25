<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SplitTest extends TestCase
{
    public function testBasicSplit()
    {
        $this->assertSame(['a','b','c'], split('a,b,c', ',', null));
    }

    public function testLimit()
    {
        $this->assertSame(['a','b,c'], split('a,b,c', ',', 2));
    }

    public function testIgnoreCase()
    {
        $this->assertSame(['A,',',C'], split('A,B,C', 'B', null, true));
        $this->assertSame(['A,',',C'], split('A,B,C', 'b', null, true));
    }

    public function testUtf8()
    {
        $this->assertSame(['é','à','ü'], split('é à ü', ' ', null, false, null, true));
    }

    public function testEmptySource()
    {
        $this->assertSame([], split('', ','));
        $this->assertSame([], split(null, ','));
    }

    public function testInvalidSeparator()
    {
        $this->expectException(InvalidArgumentException::class);
        split('abc', '');
    }
}