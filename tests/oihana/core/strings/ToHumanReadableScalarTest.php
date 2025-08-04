<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class ToHumanReadableScalarTest extends TestCase
{
    public function testNull()
    {
        $this->assertSame('null', toPhpHumanReadableScalar(null));
    }

    public function testBoolean()
    {
        $this->assertSame('true', toPhpHumanReadableScalar(true));
        $this->assertSame('false', toPhpHumanReadableScalar(false));
    }

    public function testInteger()
    {
        $this->assertSame('42', toPhpHumanReadableScalar(42));
    }

    public function testFloatWithZeroFraction()
    {
        $this->assertSame('42', toPhpHumanReadableScalar(42.0));
    }

    public function testFloatWithFraction()
    {
        $this->assertSame('3.14', toPhpHumanReadableScalar(3.14));
    }

    public function testStringSingleQuotesDefault()
    {
        $this->assertSame("'hello'", toPhpHumanReadableScalar("hello"));
    }

    public function testStringDoubleQuotes()
    {
        $this->assertSame('"He said: \"ok\""', toPhpHumanReadableScalar('He said: "ok"', 'double'));
    }

    public function testCompactOptionAffectsOnlyString()
    {
        $result = toPhpHumanReadableScalar("  spaced  ", 'single', true ) ;
        $this->assertSame("'  spaced  '" , $result);
    }

    public function testEmptyString()
    {
        $this->assertSame("''", toPhpHumanReadableScalar(""));
    }

    public function testUnusualType()
    {
        $this->assertSame("array (\n)", toPhpHumanReadableScalar([])); // handled by var_export fallback
    }

}