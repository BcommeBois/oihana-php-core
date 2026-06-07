<?php

namespace tests\oihana\core\options;

use oihana\core\options\MergeOption;
use oihana\core\options\NullsOption;
use PHPUnit\Framework\TestCase;

class MergeOptionTest extends TestCase
{
    public function testConstantsValues() : void
    {
        $this->assertSame( 'clean' , MergeOption::CLEAN ) ;
        $this->assertSame( 'deep' , MergeOption::DEEP ) ;
        $this->assertSame( 'indexed' , MergeOption::INDEXED ) ;
        $this->assertSame( 'nulls' , MergeOption::NULLS ) ;
        $this->assertSame( 'preserveKeys' , MergeOption::PRESERVE_KEYS ) ;
        $this->assertSame( 'unique' , MergeOption::UNIQUE ) ;
    }

    public function testNormalizeFillsDefaults() : void
    {
        $opts = MergeOption::normalize( [] ) ;

        $this->assertTrue( $opts[ MergeOption::DEEP ] ) ;
        $this->assertFalse( $opts[ MergeOption::INDEXED ] ) ;
        $this->assertFalse( $opts[ MergeOption::UNIQUE ] ) ;
        $this->assertNull( $opts[ MergeOption::CLEAN ] ) ;
        $this->assertTrue( $opts[ MergeOption::PRESERVE_KEYS ] ) ;
        $this->assertSame( NullsOption::SKIP , $opts[ MergeOption::NULLS ] ) ;
    }

    public function testNormalizeWithNullReturnsDefaults() : void
    {
        $opts = MergeOption::normalize( null ) ;
        $this->assertSame( NullsOption::SKIP , $opts[ MergeOption::NULLS ] ) ;
    }

    public function testNormalizePreservesValidNulls() : void
    {
        $opts = MergeOption::normalize( [ MergeOption::NULLS => NullsOption::KEEP ] ) ;
        $this->assertSame( NullsOption::KEEP , $opts[ MergeOption::NULLS ] ) ;
    }

    public function testNormalizeFallsBackOnInvalidNulls() : void
    {
        $opts = MergeOption::normalize( [ MergeOption::NULLS => 'not-a-valid-option' ] ) ;
        $this->assertSame( NullsOption::SKIP , $opts[ MergeOption::NULLS ] ) ;
    }
}
