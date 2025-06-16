<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

class IsAssociativeTest extends TestCase
{
    public function testReturnsFalseForEmptyArray(): void
    {
        $this->assertFalse( isAssociative([]) );
    }

    public function testReturnsFalseForIndexedArray(): void
    {
        $this->assertFalse(isAssociative(['a', 'b', 'c']));
        $this->assertFalse(isAssociative([0 => 'a', 1 => 'b', 2 => 'c']));
        $this->assertFalse(isAssociative([0 => 'a']));
    }

    public function testReturnsTrueForAssociativeArray(): void
    {
        $this->assertTrue(isAssociative(['a' => 1, 'b' => 2]));
        $this->assertTrue(isAssociative([10 => 'a', 20 => 'b']));
        $this->assertTrue(isAssociative(['nom' => 'Dupont', 'age' => 30]));
    }

    public function testReturnsTrueForNonSequentialNumericKeys(): void
    {
        $this->assertTrue(isAssociative([0 => 'a', 2 => 'b'])); // manque la clé 1
        $this->assertTrue(isAssociative([1 => 'a', 2 => 'b'])); // ne commence pas à 0
        $this->assertTrue(isAssociative([-1 => 'a', -2 => 'b'])); // clés négatives
    }

    public function testReturnsTrueForMixedKeys(): void
    {
        $this->assertTrue(isAssociative([0 => 'a', 'b' => 1, 2 => 'c']));
    }

    public function testReturnsFalseForStringNumericKeys(): void
    {
        $this->assertFalse(isAssociative( ['0' => 'a' , '1' => 'b' ] ) );
    }

    public function testReturnsTrueForBooleanKeys(): void
    {
        $this->assertTrue(isAssociative([true => 'a', false => 'b']));
    }

    // public function testReturnsTrueForFloatKeys(): void
    // {
    //     $array = [ 1.5 => 'a' ] ; // Note: PHP convertit 1.5 en 1 pour les clés
    //     $this->assertTrue( isAssociative( $array ) );
    // }

    public function testReturnsTrueForNullKey(): void
    {
        $array = [null => 'a'] ; // null est converti en ''
        $this->assertTrue(isAssociative($array));
    }

    public function testReturnsTrueForEmptyStringKey(): void
    {
        $this->assertTrue(isAssociative(['' => 'a']));
    }

    public function testReturnsTrueForSingleElementWithNonZeroNumericKey(): void
    {
        $this->assertTrue(isAssociative([1 => 'a']));
    }

    public function testReturnsFalseForSingleElementWithZeroKey(): void
    {
        $this->assertFalse(isAssociative([0 => 'a']));
    }
}
