<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class ToHumanReadableScalarTest extends TestCase
{
    public function testNull()
    {
        $this->assertSame('null', toHumanReadableScalar(null));
    }

    public function testBoolean()
    {
        $this->assertSame('true', toHumanReadableScalar(true));
        $this->assertSame('false', toHumanReadableScalar(false));
    }

    public function testInteger()
    {
        $this->assertSame('42', toHumanReadableScalar(42));
    }

    public function testFloatWithZeroFraction()
    {
        $this->assertSame('42', toHumanReadableScalar(42.0));
    }

    public function testFloatWithFraction()
    {
        $this->assertSame('3.14', toHumanReadableScalar(3.14));
    }

    public function testStringSingleQuotesDefault()
    {
        $this->assertSame("'hello'", toHumanReadableScalar("hello"));
    }

    public function testStringDoubleQuotes()
    {
        $this->assertSame('"He said: \"ok\""', toHumanReadableScalar('He said: "ok"', 'double'));
    }

    public function testCompactOptionAffectsOnlyString()
    {
        $result = toHumanReadableScalar("  spaced  ", 'single', true ) ;
        $this->assertSame("'  spaced  '" , $result);
    }

    public function testEmptyString()
    {
        $this->assertSame("''", toHumanReadableScalar(""));
    }

    public function testUnusualType()
    {
        $this->assertSame("array (\n)", toHumanReadableScalar([])); // handled by var_export fallback
    }

}