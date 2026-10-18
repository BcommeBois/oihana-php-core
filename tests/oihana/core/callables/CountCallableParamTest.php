<?php

namespace tests\oihana\core\callables;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use ReflectionException;
use function oihana\core\callables\countCallableParam;

class CountCallableParamClass
{
    public static function staticMethod($a, $b) {}
    public function objectMethod($x, $y, $z) {}
}

final class CountCallableParamTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testClosure(): void
    {
        $fn = function($a, $b) {};
        $this->assertSame(2, countCallableParam($fn));
    }

    /**
     * @throws ReflectionException
     */
    public function testNamedFunction(): void
    {
        $this->assertSame(1, countCallableParam('strlen'));
    }

    /**
     * @throws ReflectionException
     */
    public function testStaticMethod(): void
    {
        $this->assertSame(2, countCallableParam([ CountCallableParamClass::class, 'staticMethod' ] ) );
        $this->assertSame(2, countCallableParam('tests\oihana\core\callables\CountCallableParamClass::staticMethod'));
    }

    /**
     * @throws ReflectionException
     */
    public function testObjectMethod(): void
    {
        $obj = new CountCallableParamClass();
        $this->assertSame(3, countCallableParam([$obj, 'objectMethod']));
    }

    /**
     * @throws ReflectionException
     */
    public function testInvokableObject(): void
    {
        $obj = new class {
            public function __invoke($x, $y, $z, $w) {}
        };
        $this->assertSame(4, countCallableParam($obj));
    }

    /**
     * @throws ReflectionException
     */
    public function testCacheUsage(): void
    {
        $fn = function($a, $b, $c) {};
        // call twice, second time should use internal cache
        $this->assertSame(3, countCallableParam($fn, true));
        $this->assertSame(3, countCallableParam($fn, true));
    }

    /**
     * @throws ReflectionException
     */
    public function testDisableCache(): void
    {
        $fn = function($a, $b) {};
        $this->assertSame(2, countCallableParam($fn, false));
    }

    /**
     * 🚨 PHP hands the identifier of a freed object to the next one. A cache keyed by that
     * identifier gave a new closure the count of an old one : a two-parameter condition of
     * compress() was then called with one argument, and threw.
     *
     * @throws ReflectionException
     */
    public function testAFreedClosureNeverLendsItsCountToTheNextOne(): void
    {
        for ( $round = 0 ; $round < 50 ; $round++ )
        {
            $one = fn( $a ) => $a ;
            $this->assertSame( 1 , countCallableParam( $one ) , "round $round, one parameter" ) ;
            unset( $one ) ;

            $two = fn( $a , $b ) => $a ;
            $this->assertSame( 2 , countCallableParam( $two ) , "round $round, two parameters" ) ;
            unset( $two ) ;
        }
    }

    /**
     * An array callable was cast to a temporary object to build its cache key : two different
     * methods could meet under the same identifier.
     *
     * @throws ReflectionException
     */
    public function testArrayCallablesAreNeverConfused(): void
    {
        $obj = new CountCallableParamClass();

        for ( $round = 0 ; $round < 20 ; $round++ )
        {
            $this->assertSame( 3 , countCallableParam( [ $obj , 'objectMethod' ] ) , "round $round" ) ;
            $this->assertSame( 2 , countCallableParam( [ CountCallableParamClass::class , 'staticMethod' ] ) , "round $round" ) ;
        }
    }

    /**
     * The count of a method belongs to its class, not to an instance : any instance finds it.
     *
     * @throws ReflectionException
     */
    public function testTwoInstancesShareTheCountOfTheirMethod(): void
    {
        $this->assertSame( 3 , countCallableParam( [ new CountCallableParamClass() , 'objectMethod' ] ) ) ;
        $this->assertSame( 3 , countCallableParam( [ new CountCallableParamClass() , 'objectMethod' ] ) ) ;
    }

    /**
     * A static method written as a string and as an array is one method : both forms share one
     * entry of the cache, and the string is still read by reflection when the cache is off.
     *
     * @throws ReflectionException
     */
    public function testAStaticMethodStringAndArrayShareOneCount(): void
    {
        $string = CountCallableParamClass::class . '::staticMethod' ;

        $this->assertSame( 2 , countCallableParam( $string , false ) ) ;
        $this->assertSame( 2 , countCallableParam( $string ) ) ;
        $this->assertSame( 2 , countCallableParam( [ CountCallableParamClass::class , 'staticMethod' ] ) ) ;
    }

    /**
     * @throws ReflectionException
     */
    public function testInvalidCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        countCallableParam('nonexistentFunction');
    }
}