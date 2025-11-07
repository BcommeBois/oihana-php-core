<?php

namespace oihana\core\callables;

use PHPUnit\Framework\TestCase;

final class GetCallableTypeTest extends TestCase
{
    public function testClosure()
    {
        $closure = fn() => 42;
        $type    = getCallableType( $closure , false , $norm );

        $this->assertSame( CallableType::CLOSURE , $type );
        $this->assertSame( $closure , $norm );
    }

    public function testNamedFunction()
    {
        $type = getCallableType( 'strlen' , false , $norm );

        $this->assertSame( CallableType::FUNCTION , $type );
        $this->assertSame( 'strlen' , $norm );
    }

    public function testInvocableObject()
    {
        $obj = new class
        {
            public function __invoke() : int
            {
                return 1;
            }
        };

        $type = getCallableType( $obj , false , $norm );

        $this->assertSame( CallableType::INVOCABLE , $type );
        $this->assertSame( $obj , $norm );
    }

    public function testStaticMethod()
    {
        $class = new class { public static function foo() {} };
        $callable1 = [ get_class( $class ) , 'foo' ];
        $callable2 = get_class( $class ) . '::foo';

        $type1 = getCallableType( $callable1 , false , $norm1 );
        $type2 = getCallableType( $callable2 , false , $norm2 );

        $this->assertSame( CallableType::STATIC , $type1 );
        $this->assertSame( [ get_class($class) , 'foo' ] , $norm1 );

        $this->assertSame( CallableType::STATIC , $type2 );
        $this->assertSame( [ get_class($class) , 'foo' ] , $norm2 );
    }

    public function testObjectMethod()
    {
        $obj = new class { public function bar() {} };
        $callable = [ $obj , 'bar' ];

        $type = getCallableType( $callable , false , $norm );

        $this->assertSame( CallableType::OBJECT , $type );
        $this->assertSame( [ $obj , 'bar' ] , $norm );
    }

    public function testUnknownCallable()
    {
        $callable = new class { public static function baz() {} };
        $callableArr = [ $callable , 'baz' ];

        // Using strict=true so static method on object should be unknown
        $type = getCallableType( $callableArr , true , $norm );

        $this->assertSame( CallableType::UNKNOWN , $type );
        $this->assertSame( $callableArr , $norm );
    }

    public function testNotCallable()
    {
        $type = getCallableType( 123 , false , $norm );

        $this->assertFalse( $type );
        $this->assertSame( 123 , $norm );
    }
}