<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\ucWords;

use PHPUnit\Framework\TestCase;

class UcWordsTest extends TestCase
{
    public function testUppercasesEachWord() : void
    {
        $this->assertSame( 'Hello World' , ucWords( 'hello world' ) ) ;
    }

    public function testHandlesPunctuationBoundaries() : void
    {
        $this->assertSame( 'Foo-Bar Baz' , ucWords( 'foo-bar baz' ) ) ;
    }

    public function testIsMultibyteSafe() : void
    {
        $this->assertSame( 'Éric À Paris' , ucWords( 'éric à paris' ) ) ;
    }

    public function testLeavesRestOfWordUntouched() : void
    {
        $this->assertSame( 'HELLO World' , ucWords( 'HELLO world' ) ) ;
    }

    public function testEmptyString() : void
    {
        $this->assertSame( '' , ucWords( '' ) ) ;
    }

    public function testUcWordsWithMalformedUtf8ReturnsTheSourceUntouched()
    {
        // The /u pattern makes preg_replace_callback() return null on such a subject.
        $this->assertSame( "he\xFFllo" , ucWords( "he\xFFllo" ) );
    }
}
