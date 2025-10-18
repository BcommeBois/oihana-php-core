<?php

namespace tests\oihana\core\callables;

use PHPUnit\Framework\TestCase;

use function oihana\core\callables\wrapCallable;

final class WrapCallableTest extends TestCase
{
    /**
     * Test that wrapCallable returns a callable
     */
    public function testReturnsCallable(): void
    {
        $original = fn($x) => $x * 2;
        $wrapper = fn($fn, ...$args) => $fn(...$args);

        $result = wrapCallable($original, $wrapper);

        $this->assertNotNull($result);
    }

    /**
     * Test basic wrapping without modification
     */
    public function testBasicWrapping(): void
    {
        $original = fn($x) => $x * 2;
        $wrapper = fn($fn, ...$args) => $fn(...$args);

        $wrapped = wrapCallable($original, $wrapper);

        $this->assertEquals(10, $wrapped(5));
    }

    /**
     * Test wrapper can log before/after execution
     */
    public function testLoggingWrapper(): void
    {
        $original = fn($x) => $x * 2;
        $log = [];

        $wrapper = function($fn, ...$args) use (&$log) {
            $log[] = 'before:' . json_encode($args);
            $result = $fn(...$args);
            $log[] = 'after:' . $result;
            return $result;
        };

        $wrapped = wrapCallable($original, $wrapper);
        $result = $wrapped(5);

        $this->assertEquals(10, $result);
        $this->assertEquals(['before:[5]', 'after:10'], $log);
    }

    /**
     * Test wrapper can modify return value
     */
    public function testModifyReturnValue(): void
    {
        $original = fn($x) => $x * 2;
        $wrapper = fn($fn, ...$args) => $fn(...$args) + 100;

        $wrapped = wrapCallable($original, $wrapper);

        $this->assertEquals(110, $wrapped(5));
    }

    /**
     * Test wrapper can handle multiple arguments
     */
    public function testMultipleArguments(): void
    {
        $original = fn( $a , $b , $c ) => $a + $b + $c;
        $wrapper  = fn($fn, ...$args) => $fn(...$args) * 2;

        $wrapped = wrapCallable($original, $wrapper);

        $this->assertEquals(18, $wrapped(2, 3, 4)); // (2+3+4)*2 = 18... wait = 9*2 = 18
    }

    /**
     * Test wrapper can handle exceptions
     */
    public function testExceptionHandling(): void
    {
        $original = fn() => throw new \Exception('Test error');

        $wrapper = function($fn, ...$args) {
            try {
                return $fn(...$args);
            } catch (\Exception $e) {
                return 'error:' . $e->getMessage();
            }
        };

        $wrapped = wrapCallable($original, $wrapper);

        $this->assertEquals('error:Test error', $wrapped());
    }

    /**
     * Test wrapper with no arguments
     */
    public function testNoArguments(): void
    {
        $original = fn() => 42;
        $wrapper = fn($fn, ...$args) => $fn(...$args);

        $wrapped = wrapCallable($original, $wrapper);

        $this->assertEquals(42, $wrapped());
    }

    /**
     * Test wrapper with array callable
     */
    public function testWithArrayCallable(): void
    {
        $object = new class {
            public function getValue($x) {
                return $x * 3;
            }
        };

        $wrapper = fn($fn, ...$args) => $fn(...$args) + 5;

        $wrapped = wrapCallable([$object, 'getValue'], $wrapper);

        $this->assertEquals(20, $wrapped(5)); // (5*3)+5 = 20
    }

    /**
     * Test wrapper with static method
     */
    public function testWithStaticMethod(): void
    {
        $wrapper = fn($fn, ...$args) => strtoupper($fn(...$args));

        $wrapped = wrapCallable('strtolower', $wrapper);

        $this->assertEquals('HELLO', $wrapped('hello'));
    }

    /**
     * Test nested wrapping
     */
    public function testNestedWrapping(): void
    {
        $original = fn($x) => $x * 2;
        $wrapper1 = fn($fn, ...$args) => $fn(...$args) + 10;
        $wrapper2 = fn($fn, ...$args) => $fn(...$args) * 2;

        $wrapped1 = wrapCallable($original, $wrapper1);
        $wrapped2 = wrapCallable($wrapped1, $wrapper2);

        $this->assertEquals(40, $wrapped2(5)); // ((5*2)+10)*2 = 40
    }

    /**
     * Test wrapper receives correct arguments
     */
    public function testWrapperReceivesCorrectArguments(): void
    {
        $original = fn($a, $b) => $a . $b;
        $receivedArgs = [];

        $wrapper = function($fn, ...$args) use (&$receivedArgs) {
            $receivedArgs = $args;
            return $fn(...$args);
        };

        $wrapped = wrapCallable($original, $wrapper);
        $wrapped('hello', 'world');

        $this->assertEquals(['hello', 'world'], $receivedArgs);
    }

    /**
     * Test wrapper can skip original execution
     */
    public function testWrapperCanSkipExecution(): void
    {
        $original = fn() => throw new \Exception('Should not execute');

        $wrapper = fn($fn, ...$args) => 'skipped';

        $wrapped = wrapCallable($original, $wrapper);

        $this->assertEquals('skipped', $wrapped());
    }
}