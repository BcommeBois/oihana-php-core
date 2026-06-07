<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\ucFirst;

use PHPUnit\Framework\TestCase;

class UcFirstTest extends TestCase
{
    public function testUppercasesFirstLetter() : void
    {
        $this->assertSame( 'Hello' , ucFirst( 'hello' ) ) ;
    }

    public function testIsMultibyteSafe() : void
    {
        $this->assertSame( 'Élan' , ucFirst( 'élan' ) ) ;
    }

    public function testLeavesRestUntouched() : void
    {
        $this->assertSame( 'HELLO' , ucFirst( 'hELLO' ) ) ;
    }

    public function testEmptyString() : void
    {
        $this->assertSame( '' , ucFirst( '' ) ) ;
    }
}
