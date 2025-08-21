<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

final class PredicatesTest extends TestCase
{
    public function testNullOrEmpty()
    {
        $this->assertNull(predicates(null, 'AND'));
        $this->assertNull(predicates([], 'OR'));
    }

    public function testSingleCondition()
    {
        $conditions = [predicate('age', '>=', 18)];
        $this->assertSame('age >= 18', predicates($conditions, 'AND'));
        $this->assertSame('(age >= 18)', predicates($conditions, 'AND', true));
    }

    public function testMultipleConditions()
    {
        $conditions =
        [
            predicate('age'    , '>=' , 18       ) ,
            predicate('status' , '='  , 'active' )
        ];

        $this->assertSame('age >= 18 AND status = active', predicates($conditions, 'AND'));
        $this->assertSame('age >= 18ANDstatus = active', predicates($conditions, 'AND', spacify : false ));
        $this->assertSame('(age >= 18 AND status = active)', predicates($conditions, 'AND', true));
    }

    public function testConditionsWithEmptyValues()
    {
        $conditions = [
            predicate('age', '>=', 18),
            '',
            null,
            predicate('status', '=', 'active')
        ];

        // clean() should remove empty/null
        $this->assertSame('age >= 18 AND status = active', predicates($conditions, 'AND', false, true));
    }
}