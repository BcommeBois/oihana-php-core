<?php

namespace oihana\core\date;

use DateInvalidTimeZoneException;
use DateMalformedStringException;
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
}