<?php

declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

class BetweenTest extends TestCase
{
    public function testBetweenWithString()
    {
        $this->assertEquals('<x>', between('x', '<', '>'));
    }

    public function testBetweenWithArray()
    {
        $this->assertEquals('[a b]', between(['a', 'b'], '[', ']'));
    }

    public function testBetweenWithFlagFalse()
    {
        $this->assertEquals('y', between('y', '"', null, false));
    }

    public function testBetweenWithNullRight()
    {
        $this->assertEquals('"y"', between('y', '"'));
    }

    public function testBetweenWithNullExpression()
    {
        $this->assertEquals('""', between(null, '"'));
    }

    public function testBetweenWithEmptyString()
    {
        $this->assertEquals('""', between('', '"'));
    }

    public function testBetweenWithEmptyArray()
    {
        $this->assertEquals('[]', between([], '[', ']'));
    }

    public function testBetweenWithCustomSeparator()
    {
        $this->assertEquals('[a,b]', between(['a', 'b'], '[', ']', true, ','));
    }
}
