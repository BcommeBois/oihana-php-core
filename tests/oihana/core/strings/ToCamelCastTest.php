<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class ToCamelCastTest extends TestCase
{
    public function testBasicCases(): void
    {
        $this->assertEquals('helloWorld', toCamelCase('hello_world'));
        $this->assertEquals('helloWorld', toCamelCase('hello-world'));
        $this->assertEquals('helloWorld', toCamelCase('hello/world'));
        $this->assertEquals('helloWorld', toCamelCase('hello world')); // Note : espace non dans séparateurs par défaut, mais fonctionne comme ci-dessus
    }

    public function testAlreadyCamelCase(): void
    {
        $this->assertEquals('helloWorld', toCamelCase('helloWorld'));
        $this->assertEquals('helloWorld', toCamelCase('HelloWorld')); // première lettre mise en minuscule
    }

    public function testNoSeparators(): void
    {
        $this->assertEquals('helloworld', toCamelCase('helloworld'));
        $this->assertEquals('hello123world', toCamelCase('hello123world'));
    }

    public function testEmptyString(): void
    {
        $this->assertEquals('', toCamelCase(''));
    }

    public function testWithSpaces(): void
    {
        $this->assertEquals('helloWorld', toCamelCase('hello world')); // espace non dans séparateurs par défaut
        // Mais comme discuté, cela fonctionne car ucwords traite les espaces
    }

    public function testCustomSeparators(): void
    {
        $separators = ['_', '-', '/', ' ']; // inclut l'espace
        $this->assertEquals('helloWorld', toCamelCase('hello world', $separators));
        $this->assertEquals('helloWorld', toCamelCase('hello   world', $separators)); // plusieurs espaces
    }

    public function testConsecutiveSeparators(): void
    {
        $this->assertEquals('helloWorld', toCamelCase('hello---world'));
        $this->assertEquals('helloWorld', toCamelCase('hello___world'));
        $this->assertEquals('helloWorld', toCamelCase('hello///world'));
    }

    public function testSpecialCharacters(): void
    {
        $this->assertEquals('hello@world', toCamelCase('hello@world'));
        $this->assertEquals('helloWorld', toCamelCase('hello@world', ['@'])); // si @ est un séparateur
    }

    public function testNumbers(): void
    {
        $this->assertEquals('hello123World', toCamelCase('hello_123_world'));
    }

    public function testUnicodeCharacters(): void
    {
        $this->assertEquals('caféAuLait', toCamelCase('café_au_lait'));
    }
}