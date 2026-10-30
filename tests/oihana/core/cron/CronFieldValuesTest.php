<?php

namespace tests\oihana\core\cron ;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function oihana\core\cron\cronFieldValues;

#[CoversFunction('oihana\core\cron\cronFieldValues')]
final class CronFieldValuesTest extends TestCase
{
    /**
     * @return array<string, array{0: string, 1: int, 2: int, 3: list<int>|null}>
     */
    public static function fieldProvider() :array
    {
        return
        [
            'a star is every value'        => [ '*'       , 0 , 23 , range( 0 , 23 ) ] ,
            'a number is itself'           => [ '3'       , 0 , 23 , [ 3 ] ] ,
            'the lowest value'             => [ '0'       , 0 , 23 , [ 0 ] ] ,
            'the highest value'            => [ '23'      , 0 , 23 , [ 23 ] ] ,
            'a range'                      => [ '1-5'     , 0 , 23 , [ 1 , 2 , 3 , 4 , 5 ] ] ,
            'a range of one'               => [ '4-4'     , 0 , 23 , [ 4 ] ] ,
            'a list'                       => [ '0,30'    , 0 , 59 , [ 0 , 30 ] ] ,
            'a step over a star'           => [ '*/6'     , 0 , 23 , [ 0 , 6 , 12 , 18 ] ] ,
            'a step over a range'          => [ '0-12/4'  , 0 , 23 , [ 0 , 4 , 8 , 12 ] ] ,
            'a step over a number'         => [ '5/10'    , 0 , 59 , [ 5 ] ] ,
            'a step from the minimum'      => [ '*/15'    , 1 , 31 , [ 1 , 16 , 31 ] ] ,
            'a mix, sorted, each once'     => [ '30,0,0,5', 0 , 59 , [ 0 , 5 , 30 ] ] ,
            'a mix of a range and a step'  => [ '1-3,*/20', 0 , 59 , [ 0 , 1 , 2 , 3 , 20 , 40 ] ] ,
            'blanks around'                => [ '  7 '    , 0 , 23 , [ 7 ] ] ,
            'out of range above'           => [ '25'      , 0 , 23 , null ] ,
            'out of range below'           => [ '0'       , 1 , 31 , null ] ,
            'a range going out'            => [ '20-25'   , 0 , 23 , null ] ,
            'a backwards range'            => [ '5-1'     , 0 , 23 , null ] ,
            'a zero step'                  => [ '*/0'     , 0 , 23 , null ] ,
            'a step that is no number'     => [ '*/x'     , 0 , 23 , null ] ,
            'an empty step'                => [ '*/'      , 0 , 23 , null ] ,
            'a range missing its end'      => [ '1-'      , 0 , 23 , null ] ,
            'a negative number'            => [ '-1'      , 0 , 23 , null ] ,
            'a name'                       => [ 'mon'     , 0 , 7  , null ] ,
            'a preset'                     => [ '@daily'  , 0 , 23 , null ] ,
            'nonsense'                     => [ 'daily'   , 0 , 23 , null ] ,
            'an empty item in a list'      => [ '1,,2'    , 0 , 23 , null ] ,
            'empty'                        => [ ''        , 0 , 23 , null ] ,
            'blank'                        => [ '   '     , 0 , 23 , null ] ,
        ] ;
    }

    /**
     * @param list<int>|null $expected
     */
    #[DataProvider('fieldProvider')]
    public function testAFieldExpandsToTheValuesItMatches( string $field , int $min , int $max , ?array $expected ) :void
    {
        $this->assertSame( $expected , cronFieldValues( $field , $min , $max ) ) ;
    }

    /**
     * One part that cannot be read makes the whole field unreadable: half a schedule is not one.
     */
    public function testOneBadPartRefusesTheWholeField() :void
    {
        $this->assertNull( cronFieldValues( '0,30,99' , 0 , 59 ) ) ;
    }
}
