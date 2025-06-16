<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class LuhnTest extends TestCase
{
    public function testValidLuhnNumbers()
    {
        $this->assertTrue(luhn("49927398716"));
        $this->assertTrue(luhn("4532015112830366")); // Visa
        $this->assertTrue(luhn("6011000990139424")); // Discover
        $this->assertTrue(luhn("79927398713"));
        $this->assertTrue(luhn("378282246310005"));  // American Express
    }

    public function testInvalidLuhnNumbers()
    {
        $this->assertFalse(luhn("49927398717"));
        $this->assertFalse(luhn("4532015112830367"));
        $this->assertFalse(luhn("1234567812345678"));
        $this->assertFalse(luhn("0000000000000001"));
    }

    public function testEdgeCases()
    {
        $this->assertFalse(luhn("", true)); // vide
        $this->assertFalse(luhn("", false));
        $this->assertFalse(luhn(" ", true));
        $this->assertFalse(luhn("   ", true));
    }

    public function testNonNumericString()
    {
        $this->assertFalse(luhn("abcd1234"));
        $this->assertFalse(luhn("1234 5678 9012 3456"));
        $this->assertFalse(luhn("1234-5678"));
    }

    public function testValidLuhnNumbersLazy()
    {
        // Avec lazy = true, espaces et tirets sont tolérés
        $this->assertTrue(luhn("4532 0151 1283 0366", true));
        $this->assertTrue(luhn("4532-0151-1283-0366", true));
        $this->assertTrue(luhn("  4532-0151 1283 0366  ", true));
        $this->assertTrue(luhn("3782-822463-10005", true)); // Amex
    }

    public function testInvalidLuhnNumbersLazy()
    {
        $this->assertFalse(luhn("1234 5678 9012 3456", true)); // invalide même après nettoyage
        $this->assertFalse(luhn("invalid-number", true));     // lettres supprimées → devient vide
        $this->assertFalse(luhn("---", true));                // devient vide après nettoyage
        $this->assertFalse(luhn("0000-0000-0000-0001", true)); // mauvais checksum
    }
}