<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

use function oihana\core\date\startOfWeek;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StartOfWeekTest extends TestCase
{
    /**
     * @return array<string, array{0: string}>
     */
    public static function everyDayOfTheWeekProvider() : array
    {
        return
        [
            'Monday'    => [ '2026-03-16' ] ,
            'Tuesday'   => [ '2026-03-17' ] ,
            'Wednesday' => [ '2026-03-18' ] ,
            'Thursday'  => [ '2026-03-19' ] ,
            'Friday'    => [ '2026-03-20' ] ,
            'Saturday'  => [ '2026-03-21' ] ,
            'Sunday'    => [ '2026-03-22' ] ,
        ];
    }

    /**
     * Monday to Saturday of the same span, excluding Sunday : under a Sunday-first
     * convention Sunday itself starts the *next* week, not the one these six days share.
     *
     * @return array<string, array{0: string}>
     */
    public static function mondayToSaturdayProvider() : array
    {
        $days = self::everyDayOfTheWeekProvider() ;
        unset( $days[ 'Sunday' ] ) ;
        return $days ;
    }

    #[DataProvider( 'everyDayOfTheWeekProvider' )]
    public function testReturnsMondayForEveryDayOfTheWeekByDefault( string $date ) : void
    {
        $result = startOfWeek( new DateTimeImmutable( $date . ' 14:30:45' ) ) ;
        $this->assertSame( '2026-03-16 00:00:00.000000' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    #[DataProvider( 'mondayToSaturdayProvider' )]
    public function testReturnsSundayForMondayThroughSaturdayWithSundayFirst( string $date ) : void
    {
        $result = startOfWeek( new DateTimeImmutable( $date . ' 14:30:45' ) , 7 ) ;
        $this->assertSame( '2026-03-15 00:00:00.000000' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testSundayItselfStartsItsOwnWeekWithSundayFirst() : void
    {
        // 2026-03-22 is a Sunday : with firstDayOfWeek = 7 it is the start of the week
        // running through 2026-03-28, not the end of the previous one.
        $result = startOfWeek( new DateTimeImmutable( '2026-03-22 14:30:45' ) , 7 ) ;
        $this->assertSame( '2026-03-22 00:00:00.000000' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testRejectsAFirstDayOfWeekBelowOne() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        startOfWeek( new DateTimeImmutable( '2026-03-18' ) , 0 ) ;
    }

    public function testRejectsAFirstDayOfWeekAboveSeven() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        startOfWeek( new DateTimeImmutable( '2026-03-18' ) , 8 ) ;
    }

    public function testPreservesTheSourceTimezone() : void
    {
        $result = startOfWeek( new DateTimeImmutable( '2026-03-18' , new DateTimeZone( 'Europe/Paris' ) ) ) ;
        $this->assertSame( 'Europe/Paris' , $result->getTimezone()->getName() ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-03-18 14:30:45' ) ;
        $result = startOfWeek( $source ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-03-18 14:30:45' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-03-16 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
