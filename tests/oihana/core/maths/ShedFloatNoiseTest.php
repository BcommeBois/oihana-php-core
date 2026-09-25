<?php

namespace tests\oihana\core\maths;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function oihana\core\maths\shedFloatNoise;

/**
 * What a computed figure keeps, and what it loses, once its float noise is shed.
 */
#[CoversFunction( 'oihana\core\maths\shedFloatNoise' )]
final class ShedFloatNoiseTest extends TestCase
{
    /**
     * The sums an addition of floats renders, of three different sizes.
     *
     * @return array<string,array{0:float,1:float}>
     */
    public static function sums() :array
    {
        return
        [
            'a sum of two hundred thousand' => [ 202799.4000000001  , 202799.4 ] ,
            'a sum of one hundred thousand' => [ 154493.7699999999  , 154493.77 ] ,
            'a sum of forty five thousand'  => [ 45292.249999999985 , 45292.25 ] ,
            'a sum of two decimals'         => [ 0.1 + 0.2          , 0.3 ] ,
        ];
    }

    /**
     * The decimals a source records — five on a quantity or a weight, four on a
     * volume — are none of the noise's business.
     *
     * @return array<string,array{0:float}>
     */
    public static function measured() :array
    {
        return
        [
            'a quantity' => [ 13039.42858 ] ,
            'a weight'   => [ 55999.81828 ] ,
            'a volume'   => [ 0.0073 ] ,
            'a price'    => [ 5.86932 ] ,
            'a refund'   => [ -108.048 ] ,
        ];
    }

    /**
     * 🚨 The whole point : binary noise grows with the figure, so the scale follows
     * it. A fixed count of ten decimals would clean the third of these sums and leave
     * the first two untouched.
     */
    #[DataProvider( 'sums' )]
    public function testTheNoiseGoesWhateverTheSumWeighs( float $sum , float $expected ) :void
    {
        $this->assertSame( $expected , shedFloatNoise( $sum ) ) ;
    }

    #[DataProvider( 'measured' )]
    public function testAMeasuredDecimalSurvives( float $value ) :void
    {
        $this->assertSame( $value , shedFloatNoise( $value ) ) ;
    }

    public function testZeroAndTheVeryLargeAndTheVerySmallAreAnswered() :void
    {
        $this->assertSame( 0.0 , shedFloatNoise( 0 ) ) ;
        $this->assertSame( 0.000000123 , shedFloatNoise( 0.000000123 ) ) ;
        $this->assertSame( 12345678901234.0 , shedFloatNoise( 12345678901234.0 ) ) ;
    }

    public function testAnIntegerComesBackAsAWholeFloat() :void
    {
        $this->assertSame( 12400.0 , shedFloatNoise( 12400 ) ) ;
    }

    /**
     * Fewer digits round further into the decimals — and never into the integer part :
     * a figure is never rounded to its tens, whatever the count asked for.
     */
    public function testFewerDigitsRoundFurtherButNeverPastTheDecimalPoint() :void
    {
        $this->assertSame( 1.235    , shedFloatNoise( 1.23456789 , 4 ) ) ;
        $this->assertSame( 0.3      , shedFloatNoise( 0.1 + 0.2 , 1 ) ) ;
        $this->assertSame( 202799.0 , shedFloatNoise( 202799.4000000001 , 4 ) ) ;
    }

    public function testAnInfiniteFigureIsLeftAlone() :void
    {
        $this->assertSame( INF , shedFloatNoise( INF ) ) ;
        $this->assertNan( shedFloatNoise( NAN ) ) ;
    }
}
