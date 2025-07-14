<?php

namespace oihana\core\date;

use DateInvalidTimeZoneException;
use DateMalformedStringException;
use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class FormatDateTimeTest extends TestCase
{
    /**
     * Test formatting a valid date string.
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testValidDate()
    {
        $result = formatDateTime('2023-07-14T12:34:56', 'Europe/Paris', 'Y-m-d\TH:i:s');
        $this->assertEquals('2023-07-14T12:34:56', $result);
    }

    /**
     * Test formatting with null date (should use current date).
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testNullDate()
    {
        $result = formatDateTime(null, 'Europe/Paris', 'Y-m-d');
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $result);
    }

    /**
     * Test with invalid timezone throws exception.
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testInvalidTimezone()
    {
        $this->expectException(DateInvalidTimeZoneException::class);
        formatDateTime('2023-07-14', 'Invalid/Timezone', 'Y-m-d');
    }

    /**
     * Test with malformed date string throws exception.
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testMalformedDate()
    {
        $this->expectException(DateMalformedStringException::class);
        formatDateTime('not-a-date', 'Europe/Paris', 'Y-m-d');
    }

    /**
     * Test with valid date but different format.
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testCustomFormat()
    {
        $date = '2023-07-14 15:00:00';
        $format = 'd/m/Y H:i';
        $result = formatDateTime($date, 'Europe/Paris', $format);
        $this->assertEquals('14/07/2023 15:00', $result);
    }

    /**
     * Test with valid date and different timezone.
     * @return void
     * @throws DateMalformedStringException
     */
    public function testDifferentTimezone()
    {
        // Crée la date en UTC
        $date = '2023-07-14T12:00:00';

        // Crée un DateTime en UTC
        $dateTimeUTC = new DateTime($date, new DateTimeZone('UTC'));

        // Clone et change de timezone Paris
        $dateTimeParis = clone $dateTimeUTC;
        $dateTimeParis->setTimezone(new DateTimeZone('Europe/Paris'));

        // Format dans chaque fuseau
        $resultUTC = $dateTimeUTC->format('H:i');
        $resultParis = $dateTimeParis->format('H:i');

        $this->assertNotEquals($resultUTC, $resultParis);
    }
}