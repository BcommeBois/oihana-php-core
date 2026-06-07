<?php

namespace tests\oihana\core\strings\helpers;

use oihana\core\strings\helpers\LatinConverter;

use PHPUnit\Framework\TestCase;

class LatinConverterTest extends TestCase
{
    public function testConvertsAccentedLatin() : void
    {
        $this->assertSame( 'Cafe Munsterlander' , LatinConverter::toAscii( 'Café Münsterländer' ) ) ;
    }

    public function testConvertsSpanishTilde() : void
    {
        $this->assertSame( 'senor' , LatinConverter::toAscii( 'señor' ) ) ;
    }

    public function testConvertsGermanEszett() : void
    {
        $this->assertSame( 'strasse' , LatinConverter::toAscii( 'straße' ) ) ;
    }

    public function testConvertsLigatures() : void
    {
        $this->assertSame( 'oeuf' , LatinConverter::toAscii( 'œuf' ) ) ;
    }

    public function testConvertsCyrillic() : void
    {
        $this->assertSame( 'Privet' , LatinConverter::toAscii( 'Привет' ) ) ;
    }

    public function testLeavesPlainAsciiUnchanged() : void
    {
        $this->assertSame( 'Hello World 123' , LatinConverter::toAscii( 'Hello World 123' ) ) ;
    }

    public function testLatinsMapIsArray() : void
    {
        $this->assertIsArray( LatinConverter::LATINS ) ;
        $this->assertSame( 'a' , LatinConverter::LATINS['á'] ) ;
    }
}
