<?php

namespace tests\oihana\core\env ;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function oihana\core\env\completePath;

#[CoversFunction('oihana\core\env\completePath')]
final class CompletePathTest extends TestCase
{
    private const string STANDARD = '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin' ;

    /**
     * The PATH cron gives a job on many systems.
     */
    public function testAShortPathGainsTheStandardDirectoriesAtItsEnd() :void
    {
        $this->assertSame( '/usr/bin:/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/sbin' , completePath( '/usr/bin:/bin' ) ) ;
    }

    public function testACompletePathIsLeftAsItIs() :void
    {
        $this->assertSame( self::STANDARD , completePath( self::STANDARD ) ) ;
    }

    /**
     * A program of their own, earlier in the PATH, has to keep winning.
     */
    public function testADirectoryTheAdministratorChoseStaysInFront() :void
    {
        $this->assertStringStartsWith( '/opt/tools/bin:/usr/bin:' , completePath( '/opt/tools/bin:/usr/bin' ) ) ;
    }

    public function testNoPathGivesTheStandardOne() :void
    {
        $this->assertSame( self::STANDARD , completePath( '' ) ) ;
    }

    /**
     * What was there is kept as it was, its own repetition included: only the missing come.
     */
    public function testNothingIsAddedTwice() :void
    {
        $this->assertSame
        (
            '/usr/local/bin:/usr/bin:/usr/local/bin:/usr/local/sbin:/usr/sbin:/sbin:/bin' ,
            completePath( '/usr/local/bin:/usr/bin:/usr/local/bin' )
        ) ;
    }

    public function testEmptyEntriesAreDropped() :void
    {
        $this->assertSame( '/usr/bin:/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/sbin' , completePath( ':/usr/bin::/bin:' ) ) ;
    }

    public function testTheStandardDirectoriesCanBeChosen() :void
    {
        $this->assertSame( '/usr/bin:/opt/homebrew/bin' , completePath( '/usr/bin' , '/opt/homebrew/bin::/usr/bin' ) ) ;
    }
}
