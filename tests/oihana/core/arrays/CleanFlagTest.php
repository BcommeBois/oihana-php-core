<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;
use function oihana\core\bits\hasFlag;

class CleanFlagTest extends TestCase
{
    public function testBasicConstants(): void
    {
        $this->assertSame(0, CleanFlag::NONE);
        $this->assertSame(1, CleanFlag::NULLS);      // 1 << 0
        $this->assertSame(2, CleanFlag::EMPTY);      // 1 << 1
        $this->assertSame(4, CleanFlag::TRIM);       // 1 << 2
        $this->assertSame(8, CleanFlag::RECURSIVE);  // 1 << 3
        $this->assertSame(16, CleanFlag::EMPTY_ARR); // 1 << 4
        $this->assertSame(32, CleanFlag::FALSY);     // 1 << 5
        $this->assertSame(64, CleanFlag::RETURN_NULL);     // 1 << 6
    }

    public function testCompositeConstants(): void
    {
        $expectedDefault = CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM | CleanFlag::RECURSIVE | CleanFlag::EMPTY_ARR;
        $this->assertSame( CleanFlag::DEFAULT , $expectedDefault );

        $expectedMain = CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::EMPTY_ARR | CleanFlag::TRIM;
        $this->assertSame( CleanFlag::MAIN , $expectedMain );

        $expectedAll = CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM | CleanFlag::RECURSIVE | CleanFlag::EMPTY_ARR | CleanFlag::FALSY | CleanFlag::RETURN_NULL;
        $this->assertSame( CleanFlag::ALL , $expectedAll );
    }

    public function testFlagsArray(): void
    {
        $expectedFlags = [
            CleanFlag::NULLS,
            CleanFlag::EMPTY,
            CleanFlag::TRIM,
            CleanFlag::RECURSIVE,
            CleanFlag::EMPTY_ARR,
            CleanFlag::FALSY ,
            CleanFlag::RETURN_NULL,
        ];

        $this->assertSame( CleanFlag::FLAGS , $expectedFlags );
        $this->assertCount(7, CleanFlag::FLAGS);
    }

    public function testFlagsNameArray(): void
    {
        $expectedFlagsName = [
            CleanFlag::NULLS       => 'NULLS',
            CleanFlag::EMPTY       => 'EMPTY',
            CleanFlag::TRIM        => 'TRIM',
            CleanFlag::RECURSIVE   => 'RECURSIVE',
            CleanFlag::EMPTY_ARR   => 'EMPTY_ARR',
            CleanFlag::FALSY       => 'FALSY' ,
            CleanFlag::RETURN_NULL => 'RETURN_NULL'
        ];

        $this->assertSame( CleanFlag::FLAGS_NAME , $expectedFlagsName );
        $this->assertCount(7, CleanFlag::FLAGS_NAME);
    }

    // Tests pour la méthode has()
    public function testHasReturnsTrueWhenFlagIsPresent(): void
    {
        $mask = CleanFlag::NULLS | CleanFlag::EMPTY;

        $this->assertTrue(hasFlag($mask, CleanFlag::NULLS));
        $this->assertTrue(hasFlag($mask, CleanFlag::EMPTY));
    }

    public function testHasReturnsFalseWhenFlagIsNotPresent(): void
    {
        $mask = CleanFlag::NULLS | CleanFlag::EMPTY;

        $this->assertFalse(hasFlag($mask, CleanFlag::TRIM));
        $this->assertFalse(hasFlag($mask, CleanFlag::RECURSIVE));
        $this->assertFalse(hasFlag($mask, CleanFlag::EMPTY_ARR));
        $this->assertFalse(hasFlag($mask, CleanFlag::FALSY));
    }

    public function testHasWithNoneFlag(): void
    {
        $this->assertFalse(hasFlag(CleanFlag::NONE, CleanFlag::NULLS));
        $this->assertFalse(hasFlag(CleanFlag::NULLS, CleanFlag::NONE));
    }

    public function testHasWithAllFlags(): void
    {
        $mask = CleanFlag::ALL;

        $this->assertTrue(hasFlag($mask, CleanFlag::NULLS));
        $this->assertTrue(hasFlag($mask, CleanFlag::EMPTY));
        $this->assertTrue(hasFlag($mask, CleanFlag::TRIM));
        $this->assertTrue(hasFlag($mask, CleanFlag::RECURSIVE));
        $this->assertTrue(hasFlag($mask, CleanFlag::EMPTY_ARR));
        $this->assertTrue(hasFlag($mask, CleanFlag::FALSY));
    }

    // Tests pour la méthode isValid()
    public function testIsValidReturnsTrueForValidFlags(): void
    {
        $this->assertTrue(CleanFlag::isValid(CleanFlag::NONE));
        $this->assertTrue(CleanFlag::isValid(CleanFlag::NULLS));
        $this->assertTrue(CleanFlag::isValid(CleanFlag::EMPTY));
        $this->assertTrue(CleanFlag::isValid(CleanFlag::NULLS | CleanFlag::EMPTY));
        $this->assertTrue(CleanFlag::isValid(CleanFlag::DEFAULT));
        $this->assertTrue(CleanFlag::isValid(CleanFlag::MAIN));
        $this->assertTrue(CleanFlag::isValid(CleanFlag::ALL));
    }

    public function testIsValidReturnsFalseForInvalidFlags(): void
    {
        $this->assertFalse(CleanFlag::isValid(128));   // 1 << 7 (non existant)
        $this->assertFalse(CleanFlag::isValid(1024));  // Totalement invalide
        $this->assertFalse(CleanFlag::isValid(CleanFlag::NULLS | 128)); // Mélange valide + invalide
    }

    // Tests pour la méthode getFlags()
    public function testGetFlagsReturnsEmptyArrayForNone(): void
    {
        $this->assertSame([], CleanFlag::getFlags(CleanFlag::NONE));
    }

    public function testGetFlagsReturnsSingleFlag(): void
    {
        $this->assertSame([CleanFlag::NULLS], CleanFlag::getFlags(CleanFlag::NULLS));
        $this->assertSame([CleanFlag::EMPTY], CleanFlag::getFlags(CleanFlag::EMPTY));
        $this->assertSame([CleanFlag::FALSY], CleanFlag::getFlags(CleanFlag::FALSY));
    }

    public function testGetFlagsReturnsMultipleFlags(): void
    {
        $mask = CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM;
        $expected = [CleanFlag::NULLS, CleanFlag::EMPTY, CleanFlag::TRIM];

        $this->assertSame($expected, CleanFlag::getFlags($mask));
    }

    public function testGetFlagsReturnsAllFlags(): void
    {
        $expected = [
            CleanFlag::NULLS,
            CleanFlag::EMPTY,
            CleanFlag::TRIM,
            CleanFlag::RECURSIVE,
            CleanFlag::EMPTY_ARR,
            CleanFlag::FALSY,
            CleanFlag::RETURN_NULL
        ];

        $this->assertSame($expected, CleanFlag::getFlags(CleanFlag::ALL));
    }

    public function testGetFlagsReturnsDefaultFlags(): void
    {
        $expected = [
            CleanFlag::NULLS,
            CleanFlag::EMPTY,
            CleanFlag::TRIM,
            CleanFlag::RECURSIVE,
            CleanFlag::EMPTY_ARR
        ];

        $this->assertSame($expected, CleanFlag::getFlags(CleanFlag::DEFAULT));
    }

    // Tests pour la méthode describe()
    public function testDescribeReturnsNoneForZero(): void
    {
        $this->assertSame('NONE', CleanFlag::describe(CleanFlag::NONE));
    }

    public function testDescribeReturnsSingleFlagName(): void
    {
        $this->assertSame('NULLS', CleanFlag::describe(CleanFlag::NULLS));
        $this->assertSame('EMPTY', CleanFlag::describe(CleanFlag::EMPTY));
        $this->assertSame('TRIM', CleanFlag::describe(CleanFlag::TRIM));
        $this->assertSame('RECURSIVE', CleanFlag::describe(CleanFlag::RECURSIVE));
        $this->assertSame('EMPTY_ARR', CleanFlag::describe(CleanFlag::EMPTY_ARR));
        $this->assertSame('FALSY', CleanFlag::describe(CleanFlag::FALSY));
    }

    public function testDescribeReturnsMultipleFlagNames(): void
    {
        $mask = CleanFlag::NULLS | CleanFlag::EMPTY;
        $this->assertSame('NULLS, EMPTY', CleanFlag::describe($mask));

        $mask = CleanFlag::NULLS | CleanFlag::TRIM | CleanFlag::FALSY;
        $this->assertSame('NULLS, TRIM, FALSY', CleanFlag::describe($mask));
    }

    public function testDescribeWithCustomSeparator(): void
    {
        $mask = CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM;

        $this->assertSame('NULLS | EMPTY | TRIM', CleanFlag::describe($mask, ' | '));
        $this->assertSame('NULLS+EMPTY+TRIM', CleanFlag::describe($mask, '+'));
        $this->assertSame('NULLS; EMPTY; TRIM', CleanFlag::describe($mask, '; '));
    }

    public function testDescribeReturnsDefaultFlags(): void
    {
        $expected = 'NULLS, EMPTY, TRIM, RECURSIVE, EMPTY_ARR';
        $this->assertSame($expected, CleanFlag::describe(CleanFlag::DEFAULT));
    }

    public function testDescribeReturnsMainFlags(): void
    {
        $expected = 'NULLS, EMPTY, TRIM, EMPTY_ARR';
        $this->assertSame($expected, CleanFlag::describe(CleanFlag::MAIN));
    }

    public function testDescribeReturnsAllFlags(): void
    {
        $expected = 'NULLS, EMPTY, TRIM, RECURSIVE, EMPTY_ARR, FALSY, RETURN_NULL';
        $this->assertSame($expected, CleanFlag::describe(CleanFlag::ALL));
    }

    // Tests d'intégration
    public function testFlagConsistency(): void
    {
        // Vérifier que FLAGS contient tous les flags individuels
        $this->assertContains(CleanFlag::NULLS, CleanFlag::FLAGS);
        $this->assertContains(CleanFlag::EMPTY, CleanFlag::FLAGS);
        $this->assertContains(CleanFlag::TRIM, CleanFlag::FLAGS);
        $this->assertContains(CleanFlag::RECURSIVE, CleanFlag::FLAGS);
        $this->assertContains(CleanFlag::EMPTY_ARR, CleanFlag::FLAGS);
        $this->assertContains(CleanFlag::FALSY, CleanFlag::FLAGS);

        // Vérifier que FLAGS_NAME a les bonnes clés
        $this->assertArrayHasKey(CleanFlag::NULLS, CleanFlag::FLAGS_NAME);
        $this->assertArrayHasKey(CleanFlag::EMPTY, CleanFlag::FLAGS_NAME);
        $this->assertArrayHasKey(CleanFlag::TRIM, CleanFlag::FLAGS_NAME);
        $this->assertArrayHasKey(CleanFlag::RECURSIVE, CleanFlag::FLAGS_NAME);
        $this->assertArrayHasKey(CleanFlag::EMPTY_ARR, CleanFlag::FLAGS_NAME);
        $this->assertArrayHasKey(CleanFlag::FALSY, CleanFlag::FLAGS_NAME);
    }

    public function testAllFlagsConstantIncludesAllIndividualFlags(): void
    {
        foreach (CleanFlag::FLAGS as $flag) {
            $this->assertTrue(hasFlag(CleanFlag::ALL, $flag),
                "Flag $flag should be included in CleanFlag::ALL");
        }
    }

    // Tests edge cases
    public function testBitFlagUniqueness(): void
    {
        $flags = CleanFlag::FLAGS;
        $uniqueFlags = array_unique($flags);

        $this->assertCount(count($flags), $uniqueFlags, 'All flags should be unique');

        // Vérifier qu'aucun flag n'est 0 sauf NONE
        foreach ($flags as $flag) {
            $this->assertGreaterThan(0, $flag, 'Individual flags should be greater than 0');
        }
    }

    public function testPowerOfTwoFlags(): void
    {
        $flags = [
            CleanFlag::NULLS,
            CleanFlag::EMPTY,
            CleanFlag::TRIM,
            CleanFlag::RECURSIVE,
            CleanFlag::EMPTY_ARR,
            CleanFlag::FALSY
        ];

        foreach ($flags as $flag) {
            // Vérifier que chaque flag est une puissance de 2
            $this->assertTrue(($flag & ($flag - 1)) === 0 && $flag > 0,
                "Flag $flag should be a power of 2");
        }
    }
}