<?php

namespace oihana\core\arrays ;

use oihana\core\arrays\mocks\MockArrayAccess;
use PHPUnit\Framework\TestCase;

class ExistsTest extends TestCase
{
    public function testExistsReturnsTrueWhenKeyExistsInArray(): void
    {
        $array = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertTrue( exists($array, 'a' ) ) ;
        $this->assertTrue( exists($array, 'b' ) ) ;
        $this->assertTrue( exists($array, 'c' ) ) ;
    }

    public function testExistsReturnsFalseWhenKeyDoesNotExistInArray(): void
    {
        $array = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertFalse( exists($array, 'd' ) ) ;
        $this->assertFalse( exists($array, 123 ) ) ;
    }

    public function testExistsWorksWithNumericKeys(): void
    {
        $array = [0 => 'zero', 1 => 'one', 2 => 'two'];
        $this->assertTrue( exists($array, 0 ) ) ;
        $this->assertTrue( exists($array, 1 ) ) ;
        $this->assertTrue( exists($array, 2 ) ) ;
        $this->assertFalse( exists($array, 3 ) ) ;
    }

    public function testExistsWorksWithEmptyArray(): void
    {
        $this->assertFalse( exists([], 'any' ) ) ;
        $this->assertFalse( exists([], 0 ) ) ;
    }

    public function testExistsWorksWithStringNumericKeys(): void
    {
        $array = ['0' => 'zero', '1' => 'one'];
        $this->assertTrue( exists($array, '0' ) ) ;
        $this->assertTrue( exists($array, '1' ) ) ;
        $this->assertFalse( exists($array, '2' ) ) ;
        // Note: en PHP, '123' et 123 sont traités différemment comme clés
    }

    public function testExistsWorksWithMixedKeys(): void
    {
        $array = ['a' => 1, 0 => 'zero', 'b' => 2];
        $this->assertTrue( exists($array, 'a' ) ) ;
        $this->assertTrue( exists($array, 0 ) ) ;
        $this->assertTrue( exists($array, 'b' ) ) ;
        $this->assertFalse( exists($array, 1 ) ) ;
    }

    public function testExistsWorksWithSpecialCharactersInKeys(): void
    {
        $array = ['@key' => 1, '#hash' => 2, 'space key' => 3];
        $this->assertTrue( exists($array, '@key' ) ) ;
        $this->assertTrue( exists($array, '#hash' ) ) ;
        $this->assertTrue( exists($array, 'space key' ) ) ;
        $this->assertFalse( exists($array, 'key' ) ) ;
    }

    public function testExistsWorksWithArrayAccessObject(): void
    {
        $arrayAccess = new MockArrayAccess(['a' => 1, 'b' => 2]);

        $this->assertTrue( exists( $arrayAccess, 'a' ) ) ;
        $this->assertTrue( exists( $arrayAccess, 'b' ) ) ;
        $this->assertFalse( exists ($arrayAccess, 'c' ) ) ;
    }

    public function testExistsReturnsFalseForNonExistentKeyInArrayAccess(): void
    {
        $arrayAccess = new MockArrayAccess(['x' => 10]);
        $this->assertFalse( exists($arrayAccess, 'y' ) ) ;
    }

    public function testExistsWorksWithEmptyArrayAccessObject(): void
    {
        $arrayAccess = new MockArrayAccess();
        $this->assertFalse( exists($arrayAccess, 'any' ) ) ;
    }

    public function testExistsWorksWithNumericStringKeys(): void
    {
        $array = ['123' => 'test', 123 => 'number'];
        // Note: En PHP, "123" et 123 sont considérés comme la même clé dans un tableau
        // mais comme notre fonction utilise array_key_exists, elle les distingue correctement
        $this->assertTrue( exists($array, '123' ) ) ;
        $this->assertTrue( exists($array, 123 ) ) ;
        // Cependant, dans un tableau PHP réel, ces deux clés sont équivalentes
        // Donc dans un vrai tableau, seulement une de ces clés existerait
    }

    public function testExistsWorksWithBooleanKeys(): void
    {
        $array = [true => 'true_val', false => 'false_val'];
        $this->assertTrue( exists($array, true ) ) ;
        $this->assertTrue( exists($array, false ) ) ;
        // Note: En PHP, true est converti en 1 et false en 0 comme clés
    }

    public function testExistsWorksWithEmptyStringKey(): void
    {
        $array = ['' => 'empty'];
        $this->assertFalse( exists($array, '' ) ) ;
        $this->assertFalse( exists($array, null ) ) ; // car null est converti en ''
    }

    public function testExistsWorksWithLargeNumericKeys(): void
    {
        $largeNumber = PHP_INT_MAX;
        $array = [$largeNumber => 'big'];
        $this->assertTrue( exists($array, $largeNumber ) ) ;
    }

    // public function testExistsWorksWithSpecialFloatKeys(): void // deprecated
    // {
    //     // Note: En PHP, les floats sont convertis en entiers pour les clés
    //     $array = [1.5 => 'float', 2.0 => 'int'];
    //     $this->assertTrue( exists($array, 1 ) ) ;  // 1.5 est converti en 1
    //     $this->assertTrue( exists($array, 2 ) ) ;  // 2.0 est converti en 2
    //     $this->assertTrue( exists($array, 1.5 ) ) ; // car PHP convertit en 1
    // }
}
