<?php
namespace oihana\core\json;

use PHPUnit\Framework\TestCase;

class IsValidJsonDecodeFlagsTest extends TestCase
{
    public function testZeroIsValid(): void
    {
        $this->assertTrue(
            isValidJsonDecodeFlags(0),
            '0 (aucune option) doit être considéré comme valide.'
        );
    }

    public function testSingleValidFlags(): void
    {
        $this->assertTrue(isValidJsonDecodeFlags(JSON_BIGINT_AS_STRING));
        $this->assertTrue(isValidJsonDecodeFlags(JSON_OBJECT_AS_ARRAY));
        $this->assertTrue(isValidJsonDecodeFlags(JSON_INVALID_UTF8_IGNORE));
        $this->assertTrue(isValidJsonDecodeFlags(JSON_INVALID_UTF8_SUBSTITUTE));
        $this->assertTrue(isValidJsonDecodeFlags(JSON_THROW_ON_ERROR));
    }

    public function testMultipleValidFlags(): void
    {
        $flags = JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY;
        $this->assertTrue(
            isValidJsonDecodeFlags($flags),
            'Une combinaison de drapeaux valides doit être acceptée.'
        );
    }

    public function testInvalidFlag(): void
    {
        $invalid = 1 << 30; // bit qui ne correspond à aucun JSON_* connu
        $this->assertFalse(
            isValidJsonDecodeFlags($invalid),
            'Un drapeau inconnu doit être rejeté.'
        );
    }

    public function testMixOfValidAndInvalidFlags(): void
    {
        $invalid = JSON_THROW_ON_ERROR | (1 << 30);
        $this->assertFalse(
            isValidJsonDecodeFlags($invalid),
            'Une combinaison contenant un drapeau inconnu doit être rejetée.'
        );
    }
}