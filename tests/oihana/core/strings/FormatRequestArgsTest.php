<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

final class FormatRequestArgsTest extends TestCase
{
    public function testEmptyArrayReturnsEmptyString()
    {
        $this->assertSame('', formatRequestArgs([]));
    }

    public function testSimpleParamsWithoutUseNow()
    {
        $params = ['limit' => 10, 'page' => 2];
        $expected = '?limit=10&page=2';
        $this->assertSame($expected, formatRequestArgs($params));
    }

    public function testFromParamWithUseNowTrue()
    {
        $params = ['from' => 'yesterday', 'limit' => 5];
        $expected = '?from=now&limit=5';
        $this->assertSame($expected, formatRequestArgs($params, true));
    }

    public function testFromParamWithUseNowFalse()
    {
        $params = ['from' => 'yesterday', 'limit' => 5];
        $expected = '?from=yesterday&limit=5';
        $this->assertSame($expected, formatRequestArgs($params, false));
    }

    public function testParamsWithSpacesAndSpecialChars()
    {
        $params = ['search' => 'php functions', 'category' => 'c++'];
        $expected = '?search=php%20functions&category=c%2B%2B';
        $this->assertSame($expected, formatRequestArgs($params));
    }

    public function testJsonInFilterParam()
    {
        $json = '{"key":"category","op":"eq","val":"5"}';
        $params = ['filter' => $json];
        $expected = '?filter=%7B%22key%22%3A%22category%22%2C%22op%22%3A%22eq%22%2C%22val%22%3A%225%22%7D';
        $this->assertSame($expected, formatRequestArgs($params));
    }

    public function testMixedTypes()
    {
        $params = [
            'string' => 'test',
            'int'    => 42,
            'bool'   => true,
            'null'   => null,
        ];

        $expected = '?string=test&int=42&bool=1&null=';
        $this->assertSame($expected, formatRequestArgs($params));
    }

}