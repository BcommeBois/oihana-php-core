<?php

namespace tests\oihana\core\env ;

use PHPUnit\Framework\TestCase;

use function oihana\core\env\cpuCount;
use function oihana\core\env\isCli ;
use function oihana\core\env\isCliWithFile ;
use function oihana\core\env\isColorTerminal ;
use function oihana\core\env\isCron ;
use function oihana\core\env\isDebug ;
use function oihana\core\env\isDocker ;
use function oihana\core\env\isExtensionLoaded;
use function oihana\core\env\isInteractive ;
use function oihana\core\env\isLinux;
use function oihana\core\env\isMac;
use function oihana\core\env\isOtherOS;
use function oihana\core\env\isWeb ;
use function oihana\core\env\isWindows ;
use function oihana\core\env\phpVersion ;

class EnvTest extends TestCase
{
    public function testIsCliMatchesSapi() : void
    {
        $this->assertSame( PHP_SAPI === 'cli' , isCli() ) ;
    }

    public function testIsWebIsInverseOfIsCli() : void
    {
        $this->assertSame( !isCli() , isWeb() ) ;
    }

    public function testIsCliWithFileReturnsBool() : void
    {
        $this->assertIsBool( isCliWithFile() ) ;
    }

    public function testIsColorTerminalReturnsBool() : void
    {
        $this->assertIsBool( isColorTerminal() ) ;
    }

    public function testIsInteractiveReturnsBool() : void
    {
        $this->assertIsBool( isInteractive() ) ;
    }

    public function testIsCronReturnsBool() : void
    {
        $this->assertIsBool( isCron() ) ;
    }

    public function testIsDebugMatchesIniSetting() : void
    {
        $this->assertSame( (bool) ini_get( 'display_errors' ) , isDebug() ) ;
    }

    public function testIsDockerMatchesFilesystem() : void
    {
        $expected = file_exists( '/.dockerenv' ) || file_exists( '/.dockerinit' ) ;
        $this->assertSame( $expected , isDocker() ) ;
    }

    public function testIsExtensionLoaded() : void
    {
        $this->assertTrue( isExtensionLoaded( 'Core' ) ) ;
        $this->assertFalse( isExtensionLoaded( 'a_non_existent_extension_xyz' ) ) ;
    }

    public function testOsDetectionIsMutuallyExclusive() : void
    {
        // Exactly one of the four OS predicates must hold.
        $flags = [ isWindows() , isMac() , isLinux() , isOtherOS() ] ;
        $this->assertSame( 1 , count( array_filter( $flags ) ) ) ;
    }

    public function testOsDetectionMatchesPhpOs() : void
    {
        $this->assertSame( strtoupper( substr( PHP_OS , 0 , 3 ) ) === 'WIN'    , isWindows() ) ;
        $this->assertSame( strtoupper( substr( PHP_OS , 0 , 6 ) ) === 'DARWIN' , isMac()     ) ;
        $this->assertSame( strncasecmp( PHP_OS , 'LINUX' , 5 ) === 0          , isLinux()   ) ;
    }

    public function testIsOtherOsIsTrueOnlyWhenNoneMatch() : void
    {
        $this->assertSame( !isWindows() && !isLinux() && !isMac() , isOtherOS() ) ;
    }

    public function testCpuCountReturnsPositiveInt() : void
    {
        $count = cpuCount() ;
        $this->assertIsInt( $count ) ;
        $this->assertGreaterThanOrEqual( 1 , $count ) ;
    }

    public function testPhpVersionMatchesConstant() : void
    {
        $this->assertSame( PHP_VERSION , phpVersion() ) ;
    }

    public function testStaticCachingIsStable() : void
    {
        // A second call must return the cached value.
        $this->assertSame( isCli()      , isCli()      ) ;
        $this->assertSame( cpuCount()   , cpuCount()   ) ;
        $this->assertSame( phpVersion() , phpVersion() ) ;
        $this->assertSame( isDocker()   , isDocker()   ) ;
    }
}
