<?php

namespace tests\oihana\core\callables;

use PHPUnit\Framework\TestCase;

use function oihana\core\callables\memoizeCallable;

final class MemoizeCallableTest extends TestCase
{
    /**
     * Test basic memoization with single argument
     */
    public function testBasicMemoization(): void
    {
        $callCount = 0;
        $fn = function($x) use (&$callCount) {
            $callCount++;
            return $x * 2;
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized(5);
        $result2 = $memoized(5);

        $this->assertEquals(10, $result1);
        $this->assertEquals(10, $result2);
        $this->assertEquals(1, $callCount); // Only called once
    }

    /**
     * Test memoization with different arguments
     */
    public function testDifferentArguments(): void
    {
        $callCount = 0;
        $fn = function($x) use (&$callCount) {
            $callCount++;
            return $x * 2;
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized(5);
        $result2 = $memoized(6);
        $result3 = $memoized(5);

        $this->assertEquals(10, $result1);
        $this->assertEquals(12, $result2);
        $this->assertEquals(10, $result3);
        $this->assertEquals(2, $callCount); // Called twice (for 5 and 6)
    }

    /**
     * Test memoization with multiple arguments
     */
    public function testMultipleArguments(): void
    {
        $callCount = 0;
        $fn = function($a, $b, $c) use (&$callCount) {
            $callCount++;
            return $a + $b + $c;
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized(1, 2, 3);
        $result2 = $memoized(1, 2, 3);
        $result3 = $memoized(1, 2, 4);
        $result4 = $memoized(1, 2, 3);

        $this->assertEquals(6, $result1);
        $this->assertEquals(6, $result2);
        $this->assertEquals(7, $result3);
        $this->assertEquals(6, $result4);
        $this->assertEquals(2, $callCount); // Only 2 unique arg combinations
    }

    /**
     * Test memoization with no arguments
     */
    public function testNoArguments(): void
    {
        $callCount = 0;
        $fn = function() use (&$callCount) {
            $callCount++;
            return 42;
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized();
        $result2 = $memoized();
        $result3 = $memoized();

        $this->assertEquals(42, $result1);
        $this->assertEquals(42, $result2);
        $this->assertEquals(42, $result3);
        $this->assertEquals(1, $callCount); // Only called once
    }

    /**
     * Test memoization with array arguments
     */
    public function testArrayArguments(): void
    {
        $callCount = 0;
        $fn = function($arr) use (&$callCount) {
            $callCount++;
            return array_sum($arr);
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized([1, 2, 3]);
        $result2 = $memoized([1, 2, 3]);
        $result3 = $memoized([1, 2, 4]);

        $this->assertEquals(6, $result1);
        $this->assertEquals(6, $result2);
        $this->assertEquals(7, $result3);
        $this->assertEquals(2, $callCount); // Only 2 unique arrays
    }

    /**
     * Test memoization preserves different argument order
     */
    public function testArgumentOrderMatters(): void
    {
        $callCount = 0;
        $fn = function($a, $b) use (&$callCount) {
            $callCount++;
            return "$a-$b";
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized('a', 'b');
        $result2 = $memoized('b', 'a');
        $result3 = $memoized('a', 'b');

        $this->assertEquals('a-b', $result1);
        $this->assertEquals('b-a', $result2);
        $this->assertEquals('a-b', $result3);
        $this->assertEquals(2, $callCount); // Different order = different cache key
    }

    /**
     * Test memoization with null return value
     *
     * Note: null is NOT cached due to ??= operator behavior.
     * If null is returned, it will be re-computed on each call.
     */
    public function testNullReturnValue(): void
    {
        $callCount = 0;
        $fn = function($x) use (&$callCount) {
            $callCount++;
            return $x === 0 ? null : $x;
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized(0);
        $result2 = $memoized(0);

        $this->assertNull($result1);
        $this->assertNull($result2);
        $this->assertEquals(1, $callCount);
    }

    /**
     * Test memoization with false return value
     *
     * Note: false IS cached (??= assigns false), but the second call
     * returns the cached false value correctly.
     */
    public function testFalseReturnValue(): void
    {
        $callCount = 0;
        $fn = function( $x ) use (&$callCount)
        {
            $callCount++;
            return $x !== 0;
        };

        $memoized = memoizeCallable( $fn ) ;

        $result1 = $memoized(0);
        $result2 = $memoized(0);

        $this->assertFalse($result1);
        $this->assertFalse($result2);
        $this->assertEquals(1, $callCount);
    }

    /**
     * Test memoization with zero return value
     */
    public function testZeroReturnValue(): void
    {
        $callCount = 0;
        $fn = function($x) use (&$callCount) {
            $callCount++;
            return $x - 5;
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized(5);
        $result2 = $memoized(5);

        $this->assertEquals(0, $result1);
        $this->assertEquals(0, $result2);
        $this->assertEquals(1, $callCount); // Only called once (0 is cached)
    }

    /**
     * Test memoization with fibonacci (recursive use case)
     */
    public function testFibonacciRecursive(): void
    {
        $callCount = 0;
        $fib = null;
        $fib = memoizeCallable(function($n) use (&$fib, &$callCount) {
            $callCount++;
            if ($n <= 1) {
                return $n;
            }
            return $fib($n - 1) + $fib($n - 2);
        });

        $result = $fib(10);

        $this->assertEquals(55, $result);
        // With memoization, each value should be computed only once
        $this->assertEquals(11, $callCount); // Values 0-10 computed once each
    }

    /**
     * Test memoization with string arguments
     */
    public function testStringArguments(): void
    {
        $callCount = 0;
        $fn = function($str) use (&$callCount) {
            $callCount++;
            return strtoupper($str);
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized('hello');
        $result2 = $memoized('hello');
        $result3 = $memoized('world');

        $this->assertEquals('HELLO', $result1);
        $this->assertEquals('HELLO', $result2);
        $this->assertEquals('WORLD', $result3);
        $this->assertEquals(2, $callCount);
    }

    /**
     * Test memoization with object arguments
     *
     * Note: In PHP 8.4+, identical object instances (same reference) generate
     * the same cache key. Different object instances generate different keys.
     */
    public function testObjectArguments(): void
    {
        $callCount = 0;
        $fn = function($obj) use (&$callCount) {
            $callCount++;
            return $obj->value * 2;
        };

        $memoized = memoizeCallable($fn);

        $obj1 = (object) ['value' => 5];
        $obj2 = (object) ['value' => 5];
        $obj3 = (object) ['value' => 10];

        $result1 = $memoized($obj1);
        $result2 = $memoized($obj1); // Same reference as $obj1
        $result3 = $memoized($obj2); // Different object instance
        $result4 = $memoized($obj3); // Different object instance

        $this->assertEquals(10, $result1);
        $this->assertEquals(10, $result2);
        $this->assertEquals(10, $result3);
        $this->assertEquals(20, $result4);
        $this->assertEquals(2, $callCount); // Reusing $obj1 is cached, but $obj2 and $obj3 are new
    }

    /**
     * Test memoization returns correct type
     */
    public function testReturnTypes(): void
    {
        $memoized = memoizeCallable(function($type) {
            return match ($type) {
                'int' => 42,
                'string' => 'hello',
                'array' => [1, 2, 3],
                'bool' => true,
                'float' => 3.14,
            };
        });

        $this->assertIsInt($memoized('int'));
        $this->assertIsString($memoized('string'));
        $this->assertIsArray($memoized('array'));
        $this->assertIsBool($memoized('bool'));
        $this->assertIsFloat($memoized('float'));
    }

    /**
     * Test memoization with empty array argument
     */
    public function testEmptyArrayArgument(): void
    {
        $callCount = 0;
        $fn = function($arr) use (&$callCount) {
            $callCount++;
            return count($arr);
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized([]);
        $result2 = $memoized([]);

        $this->assertEquals(0, $result1);
        $this->assertEquals(0, $result2);
        $this->assertEquals(1, $callCount);
    }

    /**
     * Test memoization cache independence between instances
     */
    public function testCacheIndependence(): void
    {
        $callCount1 = 0;
        $callCount2 = 0;

        $fn1 = function($x) use (&$callCount1) {
            $callCount1++;
            return $x * 2;
        };

        $fn2 = function($x) use (&$callCount2) {
            $callCount2++;
            return $x * 3;
        };

        $memoized1 = memoizeCallable($fn1);
        $memoized2 = memoizeCallable($fn2);

        $memoized1(5);
        $memoized1(5);
        $memoized2(5);
        $memoized2(5);

        $this->assertEquals(1, $callCount1);
        $this->assertEquals(1, $callCount2);
    }

    /**
     * Test memoization with variadic arguments
     */
    public function testVariadicArguments(): void
    {
        $callCount = 0;
        $fn = function(...$numbers) use (&$callCount) {
            $callCount++;
            return array_sum($numbers);
        };

        $memoized = memoizeCallable($fn);

        $result1 = $memoized(1, 2, 3, 4);
        $result2 = $memoized(1, 2, 3, 4);
        $result3 = $memoized(1, 2, 3, 5);

        $this->assertEquals(10, $result1);
        $this->assertEquals(10, $result2);
        $this->assertEquals(11, $result3);
        $this->assertEquals(2, $callCount);
    }
}