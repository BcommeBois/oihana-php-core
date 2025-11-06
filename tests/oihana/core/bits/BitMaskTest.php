<?php

namespace tests\oihana\core\bits;

use PHPUnit\Framework\TestCase;

use function oihana\core\bits\countFlags;
use function oihana\core\bits\hasAllFlags;
use function oihana\core\bits\hasFlag;
use function oihana\core\bits\isValidMask;
use function oihana\core\bits\setFlag;
use function oihana\core\bits\unsetFlag;
use function oihana\core\bits\toggleFlag;

final class BitMaskTest extends TestCase
{
    public const int FLAG_A = 1 << 0; // 1
    public const int FLAG_B = 1 << 1; // 2
    public const int FLAG_C = 1 << 2; // 4

    public const int ALL_FLAGS = self::FLAG_A | self::FLAG_B | self::FLAG_C; // 7

    public function testCountFlags()
    {
        $mask = 0;
        $this->assertSame(0, countFlags($mask));

        $mask = self::FLAG_A;
        $this->assertSame(1, countFlags($mask));

        $mask = self::FLAG_A | self::FLAG_B;
        $this->assertSame(2, countFlags($mask));

        $mask = self::FLAG_A | self::FLAG_B | self::FLAG_C;
        $this->assertSame(3, countFlags($mask));

        // Masque avec bits non contigus
        $mask = 0b1010; // 2 bits actifs
        $this->assertSame(2, countFlags($mask));
    }

    public function testHasAllFlags()
    {
        $mask = self::FLAG_A | self::FLAG_B;

        // Test pour un seul flag
        $this->assertTrue(hasAllFlags($mask, self::FLAG_A));
        $this->assertTrue(hasAllFlags($mask, self::FLAG_B));
        $this->assertFalse(hasAllFlags($mask, self::FLAG_C));

        // Test pour plusieurs flags combinés
        $this->assertTrue(hasAllFlags($mask, self::FLAG_A | self::FLAG_B));
        $this->assertFalse(hasAllFlags($mask, self::FLAG_A | self::FLAG_C));
        $this->assertFalse(hasAllFlags($mask, self::FLAG_B | self::FLAG_C));
        $this->assertFalse(hasAllFlags($mask, self::FLAG_A | self::FLAG_B | self::FLAG_C));

        // Masque vide
        $this->assertFalse(hasAllFlags(0, self::FLAG_A));
    }

    public function testHasFlag()
    {
        $mask = self::FLAG_A | self::FLAG_B;

        $this->assertTrue(hasFlag($mask, self::FLAG_A));
        $this->assertTrue(hasFlag($mask, self::FLAG_B));
        $this->assertFalse(hasFlag($mask, self::FLAG_C));
    }

    public function testSetFlag()
    {
        $mask = self::FLAG_A;
        $mask = setFlag($mask, self::FLAG_B);

        $this->assertTrue(hasFlag($mask, self::FLAG_A));
        $this->assertTrue(hasFlag($mask, self::FLAG_B));
        $this->assertFalse(hasFlag($mask, self::FLAG_C));
    }

    public function testUnsetFlag()
    {
        $mask = self::FLAG_A | self::FLAG_B;
        $mask = unsetFlag($mask, self::FLAG_B);

        $this->assertTrue(hasFlag($mask, self::FLAG_A));
        $this->assertFalse(hasFlag($mask, self::FLAG_B));
        $this->assertFalse(hasFlag($mask, self::FLAG_C));
    }

    public function testToggleFlag()
    {
        $mask = self::FLAG_A;
        $mask = toggleFlag($mask, self::FLAG_B);

        // FLAG_A still set, FLAG_B toggled on
        $this->assertTrue(hasFlag($mask, self::FLAG_A));
        $this->assertTrue(hasFlag($mask, self::FLAG_B));

        // Toggle FLAG_A off and FLAG_C on
        $mask = toggleFlag($mask, self::FLAG_A);
        $mask = toggleFlag($mask, self::FLAG_C);

        $this->assertFalse(hasFlag($mask, self::FLAG_A));
        $this->assertTrue(hasFlag($mask, self::FLAG_B));
        $this->assertTrue(hasFlag($mask, self::FLAG_C));
    }

    public function testValidMask()
    {
        $mask = self::FLAG_A;
        $this->assertTrue(isValidMask($mask, self::ALL_FLAGS));

        $mask = self::FLAG_A | self::FLAG_B;
        $this->assertTrue(isValidMask($mask, self::ALL_FLAGS));

        $mask = self::ALL_FLAGS;
        $this->assertTrue(isValidMask($mask, self::ALL_FLAGS));
    }

    public function testInvalidMask()
    {
        $mask = 1 << 3; // bit non autorisé
        $this->assertFalse(isValidMask($mask, self::ALL_FLAGS));

        $mask = (self::FLAG_A | (1 << 5)); // FLAG_A valide + bit invalide
        $this->assertFalse(isValidMask($mask, self::ALL_FLAGS));

        $mask = 0b101000; // bits non autorisés
        $this->assertFalse(isValidMask($mask, self::ALL_FLAGS));
    }

    public function testEmptyMask()
    {
        $mask = 0;
        $this->assertTrue(isValidMask($mask, self::ALL_FLAGS), "Empty mask is always valid");
    }
}