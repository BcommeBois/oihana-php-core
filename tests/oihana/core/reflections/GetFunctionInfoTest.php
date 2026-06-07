<?php

namespace tests\oihana\core\reflections;

use function oihana\core\reflections\getFunctionInfo;

use Closure;
use DateTime;
use PHPUnit\Framework\TestCase;

class GetFunctionInfoTest extends TestCase
{
    public function testReturnsNullForUnknownFunction() : void
    {
        $this->assertNull( getFunctionInfo( 'a_function_that_does_not_exist_xyz' ) ) ;
    }

    public function testInternalFunction() : void
    {
        $info = getFunctionInfo( 'strlen' ) ;

        $this->assertIsArray( $info ) ;
        $this->assertSame( 'strlen' , $info['name'] ) ;
        $this->assertSame( 'strlen' , $info['alias'] ) ;
        $this->assertSame( '' , $info['namespace'] ) ;
        $this->assertTrue( $info['isInternal'] ) ;
        $this->assertFalse( $info['isUser'] ) ;
    }

    public function testUserFunction() : void
    {
        $fqn  = 'oihana\core\reflections\getFunctionInfo' ;
        $info = getFunctionInfo( $fqn ) ;

        $this->assertIsArray( $info ) ;
        $this->assertSame( $fqn , $info['name'] ) ;
        $this->assertSame( 'getFunctionInfo' , $info['alias'] ) ;
        $this->assertSame( 'oihana\core\reflections' , $info['namespace'] ) ;
        $this->assertTrue( $info['isUser'] ) ;
        $this->assertFalse( $info['isInternal'] ) ;
        $this->assertNotNull( $info['comment'] ) ;
        $this->assertIsInt( $info['startLine'] ) ;
        $this->assertIsInt( $info['endLine'] ) ;
        $this->assertStringContainsString( 'getFunctionInfo.php' , (string) $info['file'] ) ;
    }

    public function testMethodAsString() : void
    {
        $info = getFunctionInfo( 'DateTime::format' ) ;

        $this->assertIsArray( $info ) ;
        $this->assertSame( 'DateTime::format' , $info['name'] ) ;
        $this->assertSame( 'format' , $info['alias'] ) ;
        $this->assertSame( '' , $info['namespace'] ) ;
    }

    public function testMethodAsArrayWithClassName() : void
    {
        // A class-name + method array is only a valid `callable` for a static method.
        $info = getFunctionInfo( [ DateTime::class , 'createFromFormat' ] ) ;

        $this->assertIsArray( $info ) ;
        $this->assertSame( 'DateTime::createFromFormat' , $info['name'] ) ;
    }

    public function testMethodAsArrayWithInstance() : void
    {
        $info = getFunctionInfo( [ new DateTime() , 'format' ] ) ;

        $this->assertIsArray( $info ) ;
        $this->assertSame( 'DateTime::format' , $info['name'] ) ;
    }

    public function testClosure() : void
    {
        $closure = function ( int $x ) : int { return $x * 2 ; } ;
        $info    = getFunctionInfo( $closure ) ;

        $this->assertIsArray( $info ) ;
        $this->assertTrue( $info['isUser'] ) ;
        $this->assertInstanceOf( Closure::class , $closure ) ;
        $this->assertIsInt( $info['startLine'] ) ;
    }

    public function testReturnsNullForUnknownMethod() : void
    {
        $this->assertNull( getFunctionInfo( 'NonExistentClassXyz::method' ) ) ;
    }

    public function testReturnsNullForInvokableObject() : void
    {
        // An invokable object is callable but not handled (string/array/Closure) -> null.
        $invokable = new class
        {
            public function __invoke() : void {}
        } ;
        $this->assertNull( getFunctionInfo( $invokable ) ) ;
    }
}
