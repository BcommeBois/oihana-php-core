<?php

namespace tests\oihana\core\options;

use oihana\core\options\NullsOption;

use PHPUnit\Framework\TestCase;

class NullsOptionTest extends TestCase
{
    public function testConstantsValues() : void
    {
        $this->assertSame( 'skip' , NullsOption::SKIP ) ;
        $this->assertSame( 'keep' , NullsOption::KEEP ) ;
        $this->assertSame( 'overwrite' , NullsOption::OVERWRITE ) ;
    }

    public function testAllReturnsEveryOption() : void
    {
        $this->assertSame(
            [ NullsOption::SKIP , NullsOption::KEEP , NullsOption::OVERWRITE ] ,
            NullsOption::all()
        ) ;
    }

    public function testIsValidAcceptsKnownOptions() : void
    {
        $this->assertTrue( NullsOption::isValid( 'skip' ) ) ;
        $this->assertTrue( NullsOption::isValid( 'keep' ) ) ;
        $this->assertTrue( NullsOption::isValid( 'overwrite' ) ) ;
    }

    public function testIsValidRejectsUnknownOption() : void
    {
        $this->assertFalse( NullsOption::isValid( 'nope' ) ) ;
    }

    public function testIsValidIsCaseSensitive() : void
    {
        $this->assertFalse( NullsOption::isValid( 'SKIP' ) ) ;
    }
}
