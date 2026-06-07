<?php

namespace tests\oihana\core\strings;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function oihana\core\strings\parseSteps;

final class ParseStepsTest extends TestCase
{
    // ---- "all" expansions ---------------------------------------------------

    public function testNullInputReturnsAllSteps() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 4 , 5 , 6 ] , parseSteps( null , 6 ) ) ;
    }

    public function testEmptyStringReturnsAllSteps() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 4 , 5 , 6 ] , parseSteps( '' , 6 ) ) ;
    }

    public function testWhitespaceOnlyReturnsAllSteps() :void
    {
        $this->assertSame( [ 1 , 2 , 3 ] , parseSteps( '   ' , 3 ) ) ;
    }

    public function testAllKeywordReturnsAllSteps() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 4 ] , parseSteps( 'all' , 4 ) ) ;
    }

    public function testStarKeywordReturnsAllSteps() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 4 ] , parseSteps( '*' , 4 ) ) ;
    }

    // ---- Single number ------------------------------------------------------

    public function testSingleStep() :void
    {
        $this->assertSame( [ 4 ] , parseSteps( '4' , 6 ) ) ;
    }

    public function testSingleStepAtUpperBound() :void
    {
        $this->assertSame( [ 6 ] , parseSteps( '6' , 6 ) ) ;
    }

    public function testSingleStepAtLowerBound() :void
    {
        $this->assertSame( [ 1 ] , parseSteps( '1' , 6 ) ) ;
    }

    public function testSingleStepWithSurroundingWhitespace() :void
    {
        $this->assertSame( [ 4 ] , parseSteps( '  4  ' , 6 ) ) ;
    }

    // ---- Closed ranges ------------------------------------------------------

    public function testClosedRange() :void
    {
        $this->assertSame( [ 4 , 5 , 6 ] , parseSteps( '4-6' , 6 ) ) ;
    }

    public function testClosedRangeFullSpan() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 4 ] , parseSteps( '1-4' , 4 ) ) ;
    }

    public function testClosedRangeWithWhitespaceAroundHyphen() :void
    {
        $this->assertSame( [ 4 , 5 , 6 ] , parseSteps( ' 4 - 6 ' , 6 ) ) ;
    }

    public function testClosedRangeSameStartEnd() :void
    {
        $this->assertSame( [ 4 ] , parseSteps( '4-4' , 6 ) ) ;
    }

    // ---- Half-open ranges ---------------------------------------------------

    public function testOpenLeftRange() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 4 ] , parseSteps( '-4' , 6 ) ) ;
    }

    public function testOpenLeftRangeUpToMax() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 4 , 5 , 6 ] , parseSteps( '-6' , 6 ) ) ;
    }

    public function testOpenRightRange() :void
    {
        $this->assertSame( [ 5 , 6 ] , parseSteps( '5-' , 6 ) ) ;
    }

    public function testOpenRightRangeFromOne() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 4 , 5 , 6 ] , parseSteps( '1-' , 6 ) ) ;
    }

    // ---- Comma-separated lists ----------------------------------------------

    public function testCommaSeparatedSingles() :void
    {
        $this->assertSame( [ 1 , 3 , 5 ] , parseSteps( '1,3,5' , 6 ) ) ;
    }

    public function testCommaSeparatedMixedWithRange() :void
    {
        $this->assertSame( [ 1 , 3 , 5 , 6 , 7 , 10 ] , parseSteps( '1,3,5-7,10' , 10 ) ) ;
    }

    public function testCommaSeparatedDeduplicates() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 4 ] , parseSteps( '1,2-4,3,1' , 6 ) ) ;
    }

    public function testCommaSeparatedSorts() :void
    {
        $this->assertSame( [ 1 , 3 , 5 ] , parseSteps( '5,1,3' , 6 ) ) ;
    }

    public function testCommaSeparatedWithWhitespace() :void
    {
        $this->assertSame( [ 1 , 3 , 5 , 6 , 7 ] , parseSteps( '1, 3 , 5-7' , 10 ) ) ;
    }

    public function testCommaSeparatedWithHalfOpenRanges() :void
    {
        $this->assertSame( [ 1 , 2 , 3 , 5 , 6 , 7 , 8 ] , parseSteps( '-3,5,6-' , 8 ) ) ;
    }

    // ---- Invalid inputs -----------------------------------------------------

    #[DataProvider( 'invalidInputProvider' )]
    public function testInvalidInputThrows( ?string $input , int $maxStep , string $expectedMessageFragment ) :void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        $this->expectExceptionMessageMatches( '/' . preg_quote( $expectedMessageFragment , '/' ) . '/' ) ;

        parseSteps( $input , $maxStep ) ;
    }

    /**
     * @return array<string, array{0: string|null, 1: int, 2: string}>
     */
    public static function invalidInputProvider() :array
    {
        return [
            'bare hyphen'             => [ '-'      , 6 , "missing both bounds"  ] ,
            'empty token mid-list'    => [ '1,,3'   , 6 , "Empty token"           ] ,
            'empty token leading'     => [ ',1'     , 6 , "Empty token"           ] ,
            'empty token trailing'    => [ '1,'     , 6 , "Empty token"           ] ,
            'non-numeric'             => [ 'abc'    , 6 , "Invalid number 'abc'"  ] ,
            'non-numeric in token'    => [ '1,abc'  , 6 , "Invalid number 'abc'"  ] ,
            'zero'                    => [ '0'      , 6 , "out of range"          ] ,
            'above max'               => [ '7'      , 6 , "out of range"          ] ,
            'range above max'         => [ '5-9'    , 6 , "out of range"          ] ,
            'inverted range'          => [ '6-4'    , 6 , "Inverted range"        ] ,
            'open-left above max'     => [ '-9'     , 6 , "out of range"          ] ,
            'open-right above max'    => [ '9-'     , 6 , "out of range"          ] ,
            'whitespace only token'   => [ '1, ,3'  , 6 , "Empty token"           ] ,
            'double hyphen'           => [ '1--3'   , 6 , "Invalid number"        ] ,
        ] ;
    }

    public function testMaxStepZeroThrows() :void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        $this->expectExceptionMessage( 'maxStep must be >= 1' ) ;

        parseSteps( null , 0 ) ;
    }

    public function testMaxStepNegativeThrows() :void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        $this->expectExceptionMessage( 'maxStep must be >= 1' ) ;

        parseSteps( '1' , -5 ) ;
    }

    // ---- Result invariants --------------------------------------------------

    public function testResultIsAlwaysSorted() :void
    {
        $result = parseSteps( '5,1,3,2' , 10 ) ;

        $sorted = $result ;
        sort( $sorted ) ;

        $this->assertSame( $sorted , $result , 'Result must be sorted ascending' ) ;
    }

    public function testResultIsAlwaysDeduplicated() :void
    {
        $result = parseSteps( '1,1,1,2,2,3' , 10 ) ;

        $this->assertSame( array_values( array_unique( $result ) ) , $result , 'Result must contain unique values' ) ;
    }

    public function testResultIsAlwaysIndexedFromZero() :void
    {
        $result = parseSteps( '5,1,3' , 10 ) ;

        $this->assertSame( [ 0 , 1 , 2 ] , array_keys( $result ) , 'Result must be a list (zero-indexed)' ) ;
    }
}
