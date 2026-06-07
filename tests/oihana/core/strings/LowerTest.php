<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\lower;

use PHPUnit\Framework\TestCase;
use stdClass;

class LowerTest extends TestCase
{
    public function testLowerWithValidStrings()
    {
        $testCases = [
            ['HELLO', 'hello'],
            ['HeLLo WoRlD', 'hello world'],
            ['hello', 'hello'],
            ['HELLO!@#', 'hello!@#'],
            ['HÉLLÔ', 'héllô'],
            ['Café', 'café'],
            ['Straße', 'straße'],
        ];

        foreach ($testCases as $testCase) {
            $input = $testCase[0];
            $expected = $testCase[1];
            $result = lower($input);
            $this->assertEquals($expected, $result);
            $this->assertIsString($result);
        }
    }

    public function testLowerWithSpecialCases()
    {
        $testCases = [ null, '' ] ;

        foreach ($testCases as $input)
        {
            $result = lower($input);
            $this->assertEquals('' , $result ) ;
            $this->assertIsString( $result ) ;
        }
    }

    public function testFallsBackToStrtolowerOnUndetectableEncoding()
    {
        // An invalid UTF-8 byte sequence makes strict mb_detect_encoding() return
        // false, so lower() falls back to strtolower().
        $bad = "\xc3\x28" ;
        $this->assertSame( strtolower( $bad ) , lower( $bad ) ) ;
    }
}