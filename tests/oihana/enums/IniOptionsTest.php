<?php

namespace oihana\enums;

use oihana\reflections\exceptions\ConstantException;
use PHPUnit\Framework\TestCase;

class IniOptionsTest extends TestCase
{
    public function testEnumsReturnsAllIniOptionsSorted(): void
    {
        $enums = IniOptions::enums();

        $this->assertIsArray($enums);
        $sorted = $enums;
        sort($sorted, SORT_STRING);
        $this->assertSame($sorted, $enums);

        $this->assertContains('display_errors', $enums);
        $this->assertContains('memory_limit', $enums);
        $this->assertContains('upload_max_filesize', $enums);
        $this->assertContains('session.save_path', $enums);
        $this->assertContains('date.timezone', $enums);
    }

    public function testIncludesReturnsTrueForKnownOption(): void
    {
        $this->assertTrue(IniOptions::includes('display_errors'));
        $this->assertTrue(IniOptions::includes('memory_limit'));
        $this->assertFalse(IniOptions::includes('definitely_not_a_real_ini_option'));
    }

    public function testGetConstantReturnsNameForValue(): void
    {
        // Vérifie que getConstant renvoie le nom de constante pour une valeur existante
        $this->assertSame('DISPLAY_ERRORS', IniOptions::getConstant('display_errors'));
        $this->assertSame('MEMORY_LIMIT', IniOptions::getConstant('memory_limit'));

        // Inconnu => null
        $this->assertNull(IniOptions::getConstant('definitely_not_a_real_ini_option'));
    }

    public function testValidateBehavior(): void
    {
        // Ne doit pas lever d'exception pour une valeur valide
        $this->expectNotToPerformAssertions();
        IniOptions::validate('display_errors');
    }

    public function testValidateThrowsOnInvalidOption(): void
    {
        $this->expectException(ConstantException::class);
        IniOptions::validate('definitely_not_a_real_ini_option');
    }
}
