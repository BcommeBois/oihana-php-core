<?php

namespace oihana\core\arrays ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CleanValidationTest extends TestCase
{
    public function testCleanWithValidFlags(): void
    {
        $input = ['foo', '', null, 'bar'];

        // Ces appels ne devraient pas lever d'exception
        $this->assertIsArray(clean($input, CleanFlag::NONE));
        $this->assertIsArray(clean($input, CleanFlag::NULLS));
        $this->assertIsArray(clean($input, CleanFlag::EMPTY));
        $this->assertIsArray(clean($input, CleanFlag::NULLS | CleanFlag::EMPTY));
        $this->assertIsArray(clean($input, CleanFlag::DEFAULT));
        $this->assertIsArray(clean($input, CleanFlag::MAIN));
        $this->assertIsArray(clean($input, CleanFlag::ALL));
    }

    public function testCleanThrowsExceptionForInvalidFlags(): void
    {
        $input = ['foo', 'bar'];

        $this->expectException( InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid flags provided');

        clean($input, 128); // Flag invalide (1 << 6)
    }

    public function testCleanThrowsExceptionForInvalidFlagsCombination(): void
    {
        $input = ['foo', 'bar'];

        $this->expectException( InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid flags provided');

        clean($input, CleanFlag::NULLS | 128); // Mélange de flag valide et invalide
    }

    public function testCleanThrowsExceptionForLargeInvalidFlag(): void
    {
        $input = ['foo', 'bar'];

        $this->expectException( InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid flags provided');

        clean($input, 1024); // Flag complètement invalide
    }

    public function testExceptionMessageContainsHelpfulInformation(): void
    {
        $input = ['foo', 'bar'];

        try {
            clean($input, 999);
            $this->fail('Expected InvalidArgumentException was not thrown');
        } catch ( InvalidArgumentException $e) {
            $message = $e->getMessage();

            $this->assertStringContainsString('Invalid flags provided', $message);
            $this->assertStringContainsString('999', $message); // Le flag invalide
            $this->assertStringContainsString('Valid flags:', $message);
            $this->assertStringContainsString('NULLS', $message); // Au moins un flag valide
        }
    }

    public function testValidationDoesNotAffectFunctionality(): void
    {
        $input = ['foo', '', null, '   ', 'bar'];

        // Tester que la validation n'affecte pas le comportement normal
        $result = clean($input, CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM);
        $expected = ['foo', 'bar'];

        $this->assertSame($expected, $result);
    }

    public function testValidationWithEmptyArray(): void
    {
        // Même avec un tableau vide, la validation doit avoir lieu
        $this->expectException( InvalidArgumentException::class);
        clean([], 999);
    }

    /**
     * Test que tous les flags prédéfinis sont considérés comme valides
     */
    public function testAllPredefinedFlagsAreValid(): void
    {
        $input = ['test'];

        // Ces constantes doivent toutes être valides
        $predefinedFlags = [
            CleanFlag::NONE,
            CleanFlag::NULLS,
            CleanFlag::EMPTY,
            CleanFlag::TRIM,
            CleanFlag::RECURSIVE,
            CleanFlag::EMPTY_ARR,
            CleanFlag::FALSY,
            CleanFlag::DEFAULT,
            CleanFlag::MAIN,
            CleanFlag::ALL
        ];

        foreach ($predefinedFlags as $flag) {
            try {
                clean($input, $flag);
                $this->assertTrue(true); // Le flag est valide
            } catch ( InvalidArgumentException $e) {
                $this->fail("Predefined flag $flag should be valid but threw: " . $e->getMessage());
            }
        }
    }

    /**
     * Test des combinaisons communes de flags
     */
    public function testCommonFlagCombinations(): void
    {
        $input = ['foo', '', null, 'bar'];

        $commonCombinations = [
            CleanFlag::NULLS | CleanFlag::EMPTY,
            CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM,
            CleanFlag::RECURSIVE | CleanFlag::EMPTY,
            CleanFlag::RECURSIVE | CleanFlag::EMPTY_ARR,
            CleanFlag::TRIM | CleanFlag::EMPTY,
        ];

        foreach ($commonCombinations as $combination) {
            try {
                $result = clean($input, $combination);
                $this->assertIsArray($result);
            } catch ( InvalidArgumentException $e) {
                $this->fail("Common flag combination $combination should be valid but threw: " . $e->getMessage());
            }
        }
    }
}
