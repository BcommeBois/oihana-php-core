<?php

declare(strict_types=1);

namespace oihana\core\strings;

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
