<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class LatinizeTest extends TestCase
{
    public function testBasicTransliteration(): void
    {
        $this->assertSame('Cafe', latinize('Café'));
        $this->assertSame('resume', latinize('résumé'));
        $this->assertSame('Muller', latinize('Müller'));
        $this->assertSame('Garcon', latinize('Garçon'));
    }

    public function testMixedText(): void
    {
        $input = '¡Hola señor! Ça va bien? Grüß dich.';
        $expected = '¡Hola senor! Ca va bien? Gruss dich.';
        $this->assertSame($expected, latinize($input));
    }

    public function testEmptyString(): void
    {
        $this->assertSame('', latinize(''));
    }

    public function testStringWithNoAccents(): void
    {
        $this->assertSame('Hello World!', latinize('Hello World!'));
    }

    public function testMultiCharMapping(): void
    {
        // AE, oe, etc.
        $this->assertSame('AEneid', latinize('Æneid'));
        $this->assertSame('aeneid', latinize('æneid'));
        $this->assertSame('OEuvre', latinize('Œuvre'));
        $this->assertSame('oeuvre', latinize('œuvre'));
    }
}