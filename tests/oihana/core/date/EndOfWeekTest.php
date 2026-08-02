<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

use function oihana\core\date\endOfWeek;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EndOfWeekTest extends TestCase
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
    public function testReturnsSundayForEveryDayOfTheWeekByDefault( string $date ) : void
    {
        $result = endOfWeek( new DateTimeImmutable( $date . ' 14:30:45' ) ) ;
        $this->assertSame( '2026-03-22 23:59:59.999999' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    #[DataProvider( 'mondayToSaturdayProvider' )]
    public function testReturnsSaturdayForMondayThroughSaturdayWithSundayFirst( string $date ) : void
    {
        $result = endOfWeek( new DateTimeImmutable( $date . ' 14:30:45' ) , 7 ) ;
        $this->assertSame( '2026-03-21 23:59:59.999999' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testSundayStartsAWeekEndingTheFollowingSaturdayWithSundayFirst() : void
    {
        $result = endOfWeek( new DateTimeImmutable( '2026-03-22 14:30:45' ) , 7 ) ;
        $this->assertSame( '2026-03-28 23:59:59.999999' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testRejectsAnInvalidFirstDayOfWeek() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        endOfWeek( new DateTimeImmutable( '2026-03-18' ) , 8 ) ;
    }

    public function testPreservesTheSourceTimezone() : void
    {
        $result = endOfWeek( new DateTimeImmutable( '2026-03-18' , new DateTimeZone( 'Europe/Paris' ) ) ) ;
        $this->assertSame( 'Europe/Paris' , $result->getTimezone()->getName() ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-03-18 14:30:45' ) ;
        $result = endOfWeek( $source ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-03-18 14:30:45' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-03-22 23:59:59' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
