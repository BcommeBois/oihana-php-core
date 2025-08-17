<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

final class PredicateTest extends TestCase
{
    public function testOnlyLeftOperand()
    {
        $this->assertSame('age', predicate('age'));
    }

    public function testLeftOperandWithOperatorAndRightOperand()
    {
        $this->assertSame('age >= 18', predicate('age', '>=', 18));
        $this->assertSame("name = John", predicate('name', '=', 'John'));
    }

    public function testOperatorWithoutRightOperand()
    {
        $this->assertSame('status is not null', predicate('status', 'is not null'));
        $this->assertSame('status is null', predicate('status', 'is null') ) ;
    }

    public function testNullValues()
    {
        $this->assertSame('value !=', predicate('value', '!=', null));
        $this->assertSame('value', predicate('value', null, null));
    }
}