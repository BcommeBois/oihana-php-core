<?php
namespace oihana\core\json;

use PHPUnit\Framework\TestCase;

class IsValidJsonEncodeFlagsTest extends TestCase
{
    public function testZeroIsValid(): void
    {
        $this->assertTrue(
            isValidJsonEncodeFlags(0),
            '0 (aucune option) doit être considéré comme valide.'
        );
    }

    public function testSingleValidFlag(): void
    {
        $this->assertTrue(
            isValidJsonEncodeFlags(JSON_PRETTY_PRINT),
            'Un drapeau valide doit être accepté.'
        );
    }

    public function testMultipleValidFlags(): void
    {
        $flags = JSON_HEX_TAG | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR;
        $this->assertTrue(
            isValidJsonEncodeFlags($flags),
            'Une combinaison de drapeaux valides doit être acceptée.'
        );
    }

    public function testInvalidFlag(): void
    {
        $invalid = 1 << 30; // un bit qui ne correspond à aucun JSON_* connu
        $this->assertFalse(
            isValidJsonEncodeFlags($invalid),
            'Un drapeau inconnu doit être rejeté.'
        );
    }

    public function testMixOfValidAndInvalidFlags(): void
    {
        $invalid = JSON_PRETTY_PRINT | (1 << 30);
        $this->assertFalse(
            isValidJsonEncodeFlags($invalid),
            'Une combinaison contenant un drapeau inconnu doit être rejetée.'
        );
    }
}