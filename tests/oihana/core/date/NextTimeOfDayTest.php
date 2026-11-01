<?php

namespace tests\oihana\core\date ;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function oihana\core\date\nextTimeOfDay;

#[CoversFunction('oihana\core\date\nextTimeOfDay')]
final class NextTimeOfDayTest extends TestCase
{
    private const string FORMAT = 'Y-m-d H:i:s' ;

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function momentProvider() :array
    {
        return
        [
            'a later hour is the same day'           => [ '05:30' , '2026-11-01 02:00:00' , '2026-11-01 05:30:00' ] ,
            'an earlier hour is the next day'        => [ '05:30' , '2026-11-01 23:00:00' , '2026-11-02 05:30:00' ] ,
            'the very moment is the next day'        => [ '02:00' , '2026-11-01 02:00:00' , '2026-11-02 02:00:00' ] ,
            'seconds past the hour, the next day'    => [ '05:30' , '2026-11-01 05:30:30' , '2026-11-02 05:30:00' ] ,
            'a second before, the same day'          => [ '05:30' , '2026-11-01 05:29:59' , '2026-11-01 05:30:00' ] ,
            'midnight'                               => [ '00:00' , '2026-11-01 23:59:00' , '2026-11-02 00:00:00' ] ,
            'the last minute of the day'             => [ '23:59' , '2026-11-01 00:00:00' , '2026-11-01 23:59:00' ] ,
            'across the end of a month and a year'   => [ '01:15' , '2026-12-31 22:00:00' , '2027-01-01 01:15:00' ] ,
        ] ;
    }

    #[DataProvider('momentProvider')]
    public function testTheNextMomentIsStrictlyAfter( string $time , string $after , string $expected ) :void
    {
        $this->assertSame( $expected , nextTimeOfDay( $time , new DateTimeImmutable( $after ) )->format( self::FORMAT ) ) ;
    }

    public function testTheTimezoneOfTheMomentIsKept() :void
    {
        $after = new DateTimeImmutable( '2026-11-01 23:00' , new DateTimeZone( 'America/New_York' ) ) ;
        $next  = nextTimeOfDay( '05:30' , $after ) ;

        $this->assertSame( 'America/New_York' , $next->getTimezone()->getName() ) ;
        $this->assertSame( '2026-11-02 05:30:00' , $next->format( self::FORMAT ) ) ;
    }

    public function testAMutableMomentIsAcceptedAndLeftAlone() :void
    {
        $after = new DateTime( '2026-11-01 23:00' ) ;
        $next  = nextTimeOfDay( '05:30' , $after ) ;

        $this->assertSame( '2026-11-02 05:30:00' , $next->format( self::FORMAT ) ) ;
        $this->assertSame( '2026-11-01 23:00:00' , $after->format( self::FORMAT ) , 'The moment handed in was changed.' ) ;
    }

    /**
     * On the day the clocks go forward in Paris, 02:30 does not exist: PHP moves it to 03:30.
     */
    public function testAnHourTheClocksSkipIsMovedOn() :void
    {
        $paris = new DateTimeZone( 'Europe/Paris' ) ;

        $this->assertSame
        (
            '2027-03-28T03:30:00+02:00' ,
            nextTimeOfDay( '02:30' , new DateTimeImmutable( '2027-03-28 01:00' , $paris ) )->format( 'c' ) ,
            'The same day.'
        ) ;

        $this->assertSame
        (
            '2027-03-28T03:30:00+02:00' ,
            nextTimeOfDay( '02:30' , new DateTimeImmutable( '2027-03-27 23:00' , $paris ) )->format( 'c' ) ,
            'The next day.'
        ) ;
    }

    /**
     * @return array<string, array{string}>
     */
    public static function refusedProvider() :array
    {
        return
        [
            'one digit for the hour'   => [ '5:30' ] ,
            'twenty-four'              => [ '24:00' ] ,
            'twenty-five'              => [ '25:00' ] ,
            'sixty minutes'            => [ '05:60' ] ,
            'seconds'                  => [ '05:30:00' ] ,
            'words'                    => [ 'half past five' ] ,
            'nothing'                  => [ '' ] ,
            'a space in front'         => [ ' 05:30' ] ,
            'a final line break'       => [ "05:30\n" ] ,
        ] ;
    }

    #[DataProvider('refusedProvider')]
    public function testSomethingThatIsNotATimeOfDayIsRefused( string $time ) :void
    {
        try
        {
            nextTimeOfDay( $time , new DateTimeImmutable( '2026-11-01 02:00' ) ) ;
        }
        catch( InvalidArgumentException $exception )
        {
            $this->assertStringContainsString( '"' . $time . '"' , $exception->getMessage() , 'The refusal does not quote what it was given.' ) ;
            return ;
        }

        $this->fail( sprintf( '"%s" was read as a time of day.' , $time ) ) ;
    }
}
