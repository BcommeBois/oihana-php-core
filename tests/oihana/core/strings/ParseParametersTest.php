<?php declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

class ParseParametersTest extends TestCase
{
    public function testEmptyInput(): void
    {
        $this->assertSame([], parseParameters(''));
    }

    public function testSimplePairs(): void
    {
        $this->assertSame(['a' => '1', 'b' => '2'], parseParameters('a=1; b=2'));
    }

    public function testQuotedValuesAreUnquoted(): void
    {
        $this->assertSame(
            ['charset' => 'utf-8', 'boundary' => 'xyz'],
            parseParameters('charset="utf-8"; boundary=xyz')
        );
    }

    public function testSeparatorInsideQuotedValue(): void
    {
        $this->assertSame(
            ['key' => 'a;b', 'other' => 'c'],
            parseParameters('key="a;b"; other=c')
        );
    }

    public function testBareTokenHasEmptyValue(): void
    {
        $this->assertSame(['flag' => '', 'key' => 'value'], parseParameters('flag; key=value'));
    }

    public function testLowercaseKeys(): void
    {
        $this->assertSame(
            ['charset' => 'UTF-8', 'boundary' => 'xyz'],
            parseParameters('Charset=UTF-8; Boundary=xyz', ';', '=', true)
        );
    }

    public function testEmptySegmentsAreSkipped(): void
    {
        $this->assertSame(['a' => '1', 'b' => '2'], parseParameters('a=1;; b=2;'));
    }

    public function testEmptyNameIsSkipped(): void
    {
        $this->assertSame(['b' => '2'], parseParameters('=1; b=2'));
    }

    public function testCustomSeparators(): void
    {
        $this->assertSame(
            ['a' => '1', 'b' => '2'],
            parseParameters('a:1, b:2', ',', ':')
        );
    }

    public function testValueContainsKvSep(): void
    {
        // only the FIRST = splits; remainder belongs to the value
        $this->assertSame(['url' => 'https://example.com/?x=1'], parseParameters('url=https://example.com/?x=1'));
    }

    public function testMultiCharKvSep(): void
    {
        $this->assertSame(['a' => '1', 'b' => '2'], parseParameters('a => 1; b => 2', ';', '=>'));
    }

    public function testTrimmingAroundKeysAndValues(): void
    {
        $this->assertSame(['a' => '1', 'b' => '2'], parseParameters('  a = 1 ;  b = 2  '));
    }

    public function testEmptyKvSepBehavesAsBareTokens(): void
    {
        $this->assertSame(['a=1' => '', 'b=2' => ''], parseParameters('a=1; b=2', ';', ''));
    }

    public function testRfc7230LikeUsage(): void
    {
        // typical Content-Type parameter set
        $this->assertSame(
            ['charset' => 'utf-8', 'boundary' => 'a;b'],
            parseParameters('Charset="utf-8"; Boundary="a;b"', ';', '=', true)
        );
    }
}
