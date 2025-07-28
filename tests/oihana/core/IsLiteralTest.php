<?php

declare(strict_types=1);

namespace oihana\core ;

use PHPUnit\Framework\TestCase;
use stdClass;

final class IsLiteralTest extends TestCase
{
    public function testRecognizedLiterals(): void
    {
        $this->assertTrue(isLiteral('true'));
        $this->assertTrue(isLiteral('false'));
        $this->assertTrue(isLiteral('null'));
        $this->assertTrue(isLiteral('TRUE'));
        $this->assertTrue(isLiteral('False'));
        $this->assertTrue(isLiteral('NuLL'));
    }

    public function testUnrecognizedStrings(): void
    {
        $this->assertFalse(isLiteral('yes'));
        $this->assertFalse(isLiteral('no'));
        $this->assertFalse(isLiteral('undefined'));
        $this->assertFalse(isLiteral(''));
        $this->assertFalse(isLiteral('nullish'));
        $this->assertFalse(isLiteral('truee'));
        $this->assertFalse(isLiteral('False ')); // whitespace
    }

    public function testNonStringValues(): void
    {
        $this->assertFalse(isLiteral(true));
        $this->assertFalse(isLiteral(false));
        $this->assertFalse(isLiteral(null));
        $this->assertFalse(isLiteral(0));
        $this->assertFalse(isLiteral(1));
        $this->assertFalse(isLiteral([]));
        $this->assertFalse(isLiteral(new stdClass()));
    }
}