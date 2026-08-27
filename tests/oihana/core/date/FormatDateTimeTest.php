<?php

namespace tests\oihana\core\date;

use function oihana\core\date\formatDateTime;

use oihana\core\date\DateFormat;

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
    public function testNullFormatFallsBackToTheDefaultPattern(): void
    {
        $result = formatDateTime('2023-07-14T12:34:56.789', 'UTC', null);

        $this->assertSame('2023-07-14T12:34:56.789Z', $result);
    }

    /**
     * A date string carrying its own offset says which moment it is ; the default format
     * ends on a literal Z, so that moment must be converted to UTC before being labelled.
     *
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testOffsetBearingInputIsConvertedToUtcByTheDefaultFormat(): void
    {
        $this->assertSame
        (
            '2025-07-20T07:30:00.000Z' ,
            formatDateTime('2025-07-20T09:30:00+02:00')
        );
    }

    /**
     * Same rule when the offset comes from the $timezone argument rather than the string.
     *
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testNonUtcTimezoneIsConvertedToUtcByTheDefaultFormat(): void
    {
        $this->assertSame
        (
            '2025-07-20T07:30:00.000Z' ,
            formatDateTime('2025-07-20 09:30:00', 'Europe/Paris')
        );
    }

    /**
     * The explicit default pattern behaves as the implicit one.
     *
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testExplicitZuluFormatIsConvertedToUtc(): void
    {
        $this->assertSame
        (
            '2025-07-20T07:30:00Z' ,
            formatDateTime('2025-07-20 09:30:00', 'Europe/Paris', 'Y-m-d\TH:i:s\Z')
        );

        $this->assertSame
        (
            '2025-07-20T07:30:00.000Z' ,
            formatDateTime('2025-07-20 09:30:00', 'Europe/Paris', DateFormat::DEFAULT)
        );
    }

    /**
     * A format without a literal Z keeps rendering in the timezone the moment was parsed in.
     *
     * This is what the timestamped-filename helpers of `oihana/php-files` rely on.
     *
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testFormatWithoutLiteralZuluKeepsTheParsedTimezone(): void
    {
        $this->assertSame
        (
            '2025-07-20T09:30:00' ,
            formatDateTime('2025-07-20 09:30:00', 'Europe/Paris', 'Y-m-d\TH:i:s')
        );

        $this->assertSame
        (
            '2025-07-20 09:30' ,
            formatDateTime('2025-07-20 09:30:00', 'Europe/Paris', 'Y-m-d H:i')
        );
    }

    /**
     * A format carrying a real offset token says its own timezone, so it is left alone.
     *
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testOffsetBearingFormatIsLeftInItsOwnTimezone(): void
    {
        $this->assertSame
        (
            '2025-07-20T09:30:00+02:00' ,
            formatDateTime('2025-07-20T09:30:00+02:00', 'UTC', 'Y-m-d\TH:i:sP')
        );
    }

    /**
     * `\\Z` is an escaped backslash followed by the native Z token (offset in seconds),
     * not a Zulu designator — no conversion, and the token stays live.
     *
     * @return void
     * @throws DateInvalidTimeZoneException
     * @throws DateMalformedStringException
     */
    public function testEscapedBackslashBeforeZIsNotAZuluDesignator(): void
    {
        $this->assertSame
        (
            '\\7200' ,
            formatDateTime('2025-07-20 09:30:00', 'Europe/Paris', '\\\\Z')
        );
    }
}
