<?php

declare(strict_types=1);

namespace tests\oihana\core\strings;

use function oihana\core\strings\wrap;

use PHPUnit\Framework\TestCase;

class WrapTest extends TestCase
{
    public function testWrapWithDefaultChar()
    {
        $this->assertEquals('`column`', wrap('column'));
    }

    public function testWrapWithExistingChar()
    {
        $this->assertEquals('`\`column\``', wrap('`column`'));
    }

    public function testWrapWithCustomChar()
    {
        $this->assertEquals('"column"', wrap('column', '"'));
    }

    public function testWrapWithExistingCustomChar()
    {
        $this->assertEquals('"\"column\""', wrap('"column"', '"'));
    }

    public function testWrapWithEmptyString()
    {
        $this->assertEquals('``', wrap(''));
    }
}
