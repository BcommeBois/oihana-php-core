<?php

namespace tests\oihana\core\env ;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function oihana\core\env\programLocations;

/**
 * Real files in a temporary directory: two directories, a program in both, a program in the
 * second only, and a file that is there but cannot run.
 */
#[CoversFunction('oihana\core\env\programLocations')]
final class ProgramLocationsTest extends TestCase
{
    private const array FILES =
    [
        'first/tool'   => 0755 ,
        'second/tool'  => 0755 ,
        'second/other' => 0755 ,
        'first/inert'  => 0644 ,
    ] ;

    private string $root ;

    protected function setUp() :void
    {
        $this->root = sys_get_temp_dir() . '/oihana-programs-' . bin2hex( random_bytes( 6 ) ) ;

        mkdir( $this->root . '/first'  , 0700 , true ) ;
        mkdir( $this->root . '/second' , 0700 , true ) ;
        mkdir( $this->root . '/second/folder' , 0755 , true ) ;

        foreach( self::FILES as $file => $mode )
        {
            touch( $this->root . '/' . $file ) ;
            chmod( $this->root . '/' . $file , $mode ) ;
        }
    }

    protected function tearDown() :void
    {
        foreach( array_keys( self::FILES ) as $file )
        {
            @unlink( $this->root . '/' . $file ) ;
        }

        @rmdir( $this->root . '/second/folder' ) ;
        @rmdir( $this->root . '/first' ) ;
        @rmdir( $this->root . '/second' ) ;
        @rmdir( $this->root ) ;
    }

    public function testTheFirstDirectoryThatHoldsItWins() :void
    {
        $path = $this->root . '/first:' . $this->root . '/second' ;

        $this->assertSame
        (
            [ 'tool' => $this->root . '/first/tool' , 'other' => $this->root . '/second/other' , 'missing' => null ] ,
            programLocations( [ 'tool' , 'other' , 'missing' ] , $path )
        ) ;
    }

    /**
     * The shell would not run it, so it is not found — and the search goes on to the next
     * directory.
     */
    public function testAFileThatCannotRunIsNotFound() :void
    {
        $this->assertNull( programLocations( [ 'inert' ] , $this->root . '/first' )[ 'inert' ] ) ;
    }

    public function testADirectoryOfThatNameIsNotAProgram() :void
    {
        $this->assertNull( programLocations( [ 'folder' ] , $this->root . '/second' )[ 'folder' ] ) ;
    }

    public function testADirectoryThatDoesNotExistIsPassedOver() :void
    {
        $path = $this->root . '/nowhere:' . $this->root . '/second' ;

        $this->assertSame( $this->root . '/second/other' , programLocations( [ 'other' ] , $path )[ 'other' ] ) ;
    }

    public function testATrailingSlashIsNotDoubled() :void
    {
        $this->assertSame( $this->root . '/second/other' , programLocations( [ 'other' ] , $this->root . '/second/' )[ 'other' ] ) ;
    }

    /**
     * An empty segment is not the root of the disk. Shown with a name that only the root would
     * resolve: the path of a real program, without its leading slash.
     */
    public function testAnEmptySegmentIsNotTheRootDirectory() :void
    {
        $fromTheRoot = ltrim( $this->root . '/first/tool' , '/' ) ;

        $this->assertNull( programLocations( [ $fromTheRoot ] , ':' )[ $fromTheRoot ] ) ;
    }

    public function testAnEmptyPathFindsNothing() :void
    {
        $this->assertSame( [ 'tool' => null ] , programLocations( [ 'tool' ] , '' ) ) ;
    }

    public function testNoProgramsGiveAnEmptyAnswer() :void
    {
        $this->assertSame( [] , programLocations( [] , $this->root . '/first' ) ) ;
    }
}
