<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

final class FuncTest extends TestCase
{
    public function testFuncWithNoArguments()
    {
        $this->assertSame
        (
            'CALL()',
            func('CALL')
        );
    }

    public function testFuncWithSingleArgument()
    {
        $this->assertSame
        (
            'CALL(a)',
            func('CALL', 'a')
        );
    }

    public function testFuncWithArrayArguments()
    {
        $this->assertSame
        (
            'CALL(a,b,c)',
            func('CALL', ['a', 'b', 'c'])
        );
    }

    public function testFuncWithNonStringArguments()
    {
        $this->assertSame
        (
            'CALL(1,true)',
            func('CALL', [1, true, null])
        );
    }

    public function testFuncWithCustomSeparator()
    {
        $this->assertSame
        (
            'CALL(a|b|c)',
            func('CALL', ['a', 'b', 'c'], '|')
        );
    }

    public function testFuncWithNestedArrays()
    {
        $this->assertSame(
            'CALL(a,b,c)',
            func('CALL', ['a', ['b', 'c']])
        );
    }
}