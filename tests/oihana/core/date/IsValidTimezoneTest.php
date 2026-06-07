<?php

namespace tests\oihana\core\date;

use function oihana\core\date\isValidTimezone;

use PHPUnit\Framework\TestCase;

class IsValidTimezoneTest extends TestCase
{
    public function testReturnsTrueForAValidTimezone(): void
    {
        $this->assertTrue( isValidTimezone('Europe/Paris') );
        $this->assertTrue( isValidTimezone('America/New_York') );
        $this->assertTrue( isValidTimezone('Asia/Tokyo') );
    }

    public function testReturnsFalseForAnInvalidTimezone(): void
    {
        $this->assertFalse(isValidTimezone('Invalid/Timezone'));
        $this->assertFalse(isValidTimezone('NotARealTimezone'));
        $this->assertFalse(isValidTimezone('XYZ/ABC'));
    }

    public function testReturnsFalseWhenTimezoneIsNull(): void
    {
        $this->assertFalse(isValidTimezone());
    }
}