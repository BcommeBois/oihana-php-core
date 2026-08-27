<?php

namespace tests\oihana\core\date;

use function oihana\core\date\now;

use DateInvalidTimeZoneException;
use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

final class NowTest extends TestCase
{
    /**
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testNowReturnsValidString()
    {
        $date = now();
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/',
            $date,
            'now() should return ISO 8601 UTC with milliseconds'
        );
    }

    /**
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testNowWithCustomTimezone()
    {
        $date = now('Europe/Paris');
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/',
            $date,
            'now() with Europe/Paris should still return UTC formatted string'
        );
    }

    /**
     * The default format ends on a literal Z, so the string must be the real UTC moment —
     * not the Paris wall clock wearing a Z, which used to be two hours off.
     *
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     * @throws \Exception
     */
    public function testNowWithCustomTimezoneStampsTheRealUtcMoment(): void
    {
        $stamped = new DateTimeImmutable( now('Europe/Paris') , new DateTimeZone('UTC') );

        $this->assertLessThanOrEqual
        (
            5 ,
            abs( $stamped->getTimestamp() - time() ) ,
            'now() with Europe/Paris should stamp the current UTC moment, not the Paris wall clock'
        );
    }

    /**
     * Whichever timezone reads the clock, the moment stamped is the same one.
     *
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     * @throws \Exception
     */
    public function testNowIsTheSameMomentWhateverTheTimezone(): void
    {
        $utc      = new DateTimeImmutable( now() , new DateTimeZone('UTC') );
        $paris    = new DateTimeImmutable( now('Europe/Paris') , new DateTimeZone('UTC') );
        $newYork  = new DateTimeImmutable( now('America/New_York') , new DateTimeZone('UTC') );

        $this->assertLessThanOrEqual( 5 , abs( $paris->getTimestamp() - $utc->getTimestamp() ) );
        $this->assertLessThanOrEqual( 5 , abs( $newYork->getTimestamp() - $utc->getTimestamp() ) );
    }
}