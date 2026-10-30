<?php

namespace tests\oihana\core\cron ;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function oihana\core\cron\cronScheduleGap;

#[CoversFunction('oihana\core\cron\cronScheduleGap')]
final class CronScheduleGapTest extends TestCase
{
    private const int HOUR = 3600 ;
    private const int DAY  = 86400 ;

    /**
     * @return array<string, array{0: string, 1: int|null}>
     */
    public static function scheduleProvider() :array
    {
        return
        [
            // The usual presets, written out.
            'daily'                    => [ '17 2 * * *'     , self::DAY ] ,
            'weekly'                   => [ '17 3 * * 0'     , 7 * self::DAY ] ,
            'weekly on a 7'            => [ '17 3 * * 7'     , 7 * self::DAY ] ,
            'monthly'                  => [ '17 4 1 * *'     , 31 * self::DAY ] ,
            'hourly'                   => [ '0 * * * *'      , self::HOUR ] ,
            'every minute'             => [ '* * * * *'      , 60 ] ,

            // What an operator writes by hand.
            'six-hourly'               => [ '0 */6 * * *'    , 6 * self::HOUR ] ,
            'every quarter hour'       => [ '*/15 * * * *'   , 15 * 60 ] ,
            'twice a day'              => [ '0 2,14 * * *'   , 12 * self::HOUR ] ,
            'twice a day, unevenly'    => [ '0 2,8 * * *'    , 18 * self::HOUR ] ,
            'twice an hour, at night'  => [ '0,30 1-3 * * *' , 21 * self::HOUR + 30 * 60 ] ,

            // The case that decides the threshold: five runs a week, and 72 hours across the
            // weekend. Answering "about 33 hours" would alert every Sunday.
            'weekdays only'            => [ '30 2 * * 1-5'   , 72 * self::HOUR ] ,
            'mondays and thursdays'    => [ '0 2 * * 1,4'    , 4 * self::DAY ] ,
            'sunday as 0 and as 7'     => [ '0 2 * * 0,7'    , 7 * self::DAY ] ,

            // Measured pessimistically: a job on the 31st skips the short months.
            'the last day of a month'  => [ '0 2 31 * *'     , 31 * self::DAY ] ,
            'the 1st and the 15th'     => [ '0 2 1,15 * *'   , 17 * self::DAY ] ,

            // Every day of the month and every day of the week: a daily job.
            'every day, both written'  => [ '0 2 1-31 * 0-6' , self::DAY ] ,
            'every month, written'     => [ '0 2 * 1-12 *'   , self::DAY ] ,

            // Extra blanks are blanks.
            'tabs and spaces'          => [ "  17\t2  * * *  " , self::DAY ] ,

            // Refused rather than guessed.
            'a yearly schedule'        => [ '0 0 1 1 *'      , null ] ,
            'some months only'         => [ '0 2 * 1-6 *'    , null ] ,
            'day of month and week'    => [ '0 2 15 * 1'     , null ] ,
            'four fields'              => [ '0 2 * *'        , null ] ,
            'six fields'               => [ '0 0 2 * * *'    , null ] ,
            'a preset name'            => [ '@daily'         , null ] ,
            'an unreadable minute'     => [ '61 2 * * *'     , null ] ,
            'an unreadable hour'       => [ '0 24 * * *'     , null ] ,
            'an unreadable day'        => [ '0 2 32 * *'     , null ] ,
            'an unreadable month'      => [ '0 2 * 13 *'     , null ] ,
            'an unreadable weekday'    => [ '0 2 * * 8'      , null ] ,
            'empty'                    => [ ''               , null ] ,
        ] ;
    }

    #[DataProvider('scheduleProvider')]
    public function testTheLongestGapAScheduleLeaves( string $expression , ?int $expected ) :void
    {
        $this->assertSame( $expected , cronScheduleGap( $expression ) ) ;
    }

    /**
     * The gap that wraps around the end of the cycle counts like the others: from the last run of
     * a cycle to the first of the next.
     */
    public function testTheGapAcrossTheEndOfTheCycleCounts() :void
    {
        // 22:00 and 23:00 each day: the long gap is from 23:00 to 22:00 the next day.

        $this->assertSame( 23 * self::HOUR , cronScheduleGap( '0 22,23 * * *' ) ) ;
    }
}
