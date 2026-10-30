<?php

namespace tests\oihana\core\arrays ;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function oihana\core\arrays\deepMerge;
use function oihana\core\arrays\overlay;

#[CoversFunction('oihana\core\arrays\overlay')]
final class OverlayTest extends TestCase
{
    /**
     * The rule the function exists for: a list declared on top replaces the base one, so it can
     * be shorter.
     */
    public function testAListReplacesTheBaseOne() :void
    {
        $this->assertSame
        (
            [ 'plugins' => [ 'akismet' ] ] ,
            overlay( [ 'plugins' => [ 'a' , 'b' , 'c' ] ] , [ 'plugins' => [ 'akismet' ] ] )
        ) ;
    }

    /**
     * Including the empty one, which is how an override says "none".
     */
    public function testAnEmptyListMeansNone() :void
    {
        $this->assertSame( [] , overlay( [ 'plugins' => [ 'a' , 'b' ] ] , [ 'plugins' => [] ] )[ 'plugins' ] ) ;
    }

    /**
     * Not index by index, as `array_replace_recursive()` would: that gives a list nobody wrote.
     */
    public function testAShorterListIsNotMergedIndexByIndex() :void
    {
        $base = [ 'list' => [ 'a' , 'b' , 'c' ] ] ;
        $over = [ 'list' => [ 'x' ] ] ;

        $this->assertSame( [ 'x' , 'b' , 'c' ] , array_replace_recursive( $base , $over )[ 'list' ] , 'The behaviour avoided.' ) ;
        $this->assertSame( [ 'x' ] , overlay( $base , $over )[ 'list' ] ) ;
    }

    /**
     * Nor appended, as `deepMerge()` does: the base could then never be shortened.
     */
    public function testAListIsNotAppendedToTheBaseOne() :void
    {
        $base = [ 'list' => [ 'a' , 'b' ] ] ;
        $over = [ 'list' => [ 'c' ] ] ;

        $this->assertSame( [ 'a' , 'b' , 'c' ] , deepMerge( $base , $over )[ 'list' ] , 'The behaviour avoided.' ) ;
        $this->assertSame( [ 'c' ] , overlay( $base , $over )[ 'list' ] ) ;
    }

    public function testATableIsMergedKeyByKey() :void
    {
        $this->assertSame
        (
            [ 'install' => [ 'path' => '/var/www' , 'activate' => true , 'quiet' => false ] ] ,
            overlay
            (
                [ 'install' => [ 'path' => '/var/www' , 'activate' => true , 'quiet' => true ] ] ,
                [ 'install' => [ 'quiet' => false ] ]
            )
        ) ;
    }

    public function testTablesAreMergedAtEveryDepth() :void
    {
        $this->assertSame
        (
            [ 'a' => [ 'b' => [ 'c' => 1 , 'd' => 3 ] , 'e' => [ 'x' ] ] ] ,
            overlay
            (
                [ 'a' => [ 'b' => [ 'c' => 1 , 'd' => 2 ] , 'e' => [ 'y' , 'z' ] ] ] ,
                [ 'a' => [ 'b' => [ 'd' => 3 ] , 'e' => [ 'x' ] ] ]
            )
        ) ;
    }

    public function testWhatTheOverDoesNotMentionIsUntouched() :void
    {
        $base = [ 'a' => 1 , 'b' => [ 'c' => 2 ] ] ;

        $this->assertSame( $base , overlay( $base , [] ) ) ;
    }

    public function testAKeyOnlyTheOverHasIsAdded() :void
    {
        $this->assertSame( [ 'a' => 1 , 'b' => [ 'c' => 2 ] ] , overlay( [ 'a' => 1 ] , [ 'b' => [ 'c' => 2 ] ] ) ) ;
    }

    public function testAScalarReplacesATable() :void
    {
        $this->assertSame( [ 'a' => false ] , overlay( [ 'a' => [ 'b' => 1 ] ] , [ 'a' => false ] ) ) ;
    }

    public function testATableReplacesAScalar() :void
    {
        $this->assertSame( [ 'a' => [ 'b' => 1 ] ] , overlay( [ 'a' => 'text' ] , [ 'a' => [ 'b' => 1 ] ] ) ) ;
    }

    public function testANullIsAValueToo() :void
    {
        $this->assertSame( [ 'a' => null , 'b' => 2 ] , overlay( [ 'a' => 1 , 'b' => 2 ] , [ 'a' => null ] ) ) ;
    }

    public function testTheBaseIsNotChanged() :void
    {
        $base = [ 'a' => [ 'b' => 1 ] ] ;

        overlay( $base , [ 'a' => [ 'b' => 2 ] ] ) ;

        $this->assertSame( [ 'a' => [ 'b' => 1 ] ] , $base ) ;
    }

    /**
     * The order of the base's keys is kept; the new ones come after.
     */
    public function testTheOrderOfTheKeysIsKept() :void
    {
        $this->assertSame( [ 'a' , 'b' , 'c' ] , array_keys( overlay( [ 'a' => 1 , 'b' => 2 ] , [ 'c' => 3 , 'a' => 0 ] ) ) ) ;
    }
}
