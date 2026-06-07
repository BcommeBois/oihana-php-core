<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\capitalize;

use PHPUnit\Framework\TestCase;

class CapitalizeTest extends TestCase
{
    public function testUppercasesFirstAndLowercasesRest() : void
    {
        $this->assertSame( 'Hello' , capitalize( 'hELLO' ) ) ;
    }

    public function testOnlyAffectsFirstWord() : void
    {
        $this->assertSame( 'Hello world' , capitalize( 'hello world' ) ) ;
    }

    public function testIsMultibyteSafe() : void
    {
        $this->assertSame( 'Élan' , capitalize( 'ÉLAN' ) ) ;
    }

    public function testEmptyString() : void
    {
        $this->assertSame( '' , capitalize( '' ) ) ;
    }
}
