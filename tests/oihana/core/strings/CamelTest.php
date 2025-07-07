<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class CamelTest extends TestCase
{
    public function testBasicCases(): void
    {
        $this->assertEquals('helloWorld', camel('hello_world'));
        $this->assertEquals('helloWorld', camel('hello-world'));
        $this->assertEquals('helloWorld', camel('hello/world'));
        $this->assertEquals('helloWorld', camel('hello world')); // Note : espace non dans séparateurs par défaut, mais fonctionne comme ci-dessus
    }

    public function testAlreadyCamelCase(): void
    {
        $this->assertEquals('helloWorld', camel('helloWorld'));
        $this->assertEquals('helloWorld', camel('HelloWorld')); // première lettre mise en minuscule
    }

    public function testNoSeparators(): void
    {
        $this->assertEquals('helloworld', camel('helloworld'));
        $this->assertEquals('hello123world', camel('hello123world'));
    }

    public function testEmptyString(): void
    {
        $this->assertEquals('', camel(''));
    }

    public function testWithSpaces(): void
    {
        $this->assertEquals('helloWorld', camel('hello world')); // espace non dans séparateurs par défaut
        // Mais comme discuté, cela fonctionne car ucwords traite les espaces
    }

    public function testCustomSeparators(): void
    {
        $separators = ['_', '-', '/', ' ']; // inclut l'espace
        $this->assertEquals('helloWorld', camel('hello world', $separators));
        $this->assertEquals('helloWorld', camel('hello   world', $separators)); // plusieurs espaces
    }

    public function testConsecutiveSeparators(): void
    {
        $this->assertEquals('helloWorld', camel('hello---world'));
        $this->assertEquals('helloWorld', camel('hello___world'));
        $this->assertEquals('helloWorld', camel('hello///world'));
    }

    public function testSpecialCharacters(): void
    {
        $this->assertEquals('hello@world', camel('hello@world'));
        $this->assertEquals('helloWorld', camel('hello@world', ['@'])); // si @ est un séparateur
    }

    public function testNumbers(): void
    {
        $this->assertEquals('hello123World', camel('hello_123_world'));
    }

    public function testUnicodeCharacters(): void
    {
        $this->assertEquals('caféAuLait', camel('café_au_lait'));
    }
}