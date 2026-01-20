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
     * @throws ReflectionException
     */
    public function testInvalidCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        countCallableParam('nonexistentFunction');
    }
}