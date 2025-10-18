<?php

namespace tests\oihana\core\callables;

use PHPUnit\Framework\TestCase;

use stdClass;
use function oihana\core\callables\chainCallables;

final class ChainCallableTest extends TestCase
{
    /**
     * Test that empty array returns null
     */
    public function testEmptyArrayReturnsNull(): void
    {
        $result = chainCallables([]);

        $this->assertNull($result);
    }

    /**
     * Test that chainCallables returns a callable
     */
    public function testReturnsCallable(): void
    {
        $chain = chainCallables([
            fn($x) => $x * 2,
            fn($x) => $x + 10,
        ]);

        $this->assertTrue(is_callable($chain));
    }

    /**
     * Test single callable in chain
     */
    public function testSingleCallable(): void
    {
        $chain = chainCallables([
            fn($x) => $x * 2,
        ]);

        $this->assertEquals(10, $chain(5));
    }

    /**
     * Test multiple closure callables
     */
    public function testMultipleClosures(): void
    {
        $chain = chainCallables([
            fn($x) => $x * 2,
            fn($x) => $x + 10,
            fn($x) => sqrt($x),
        ]);

        // (5 * 2) = 10, 10 + 10 = 20, sqrt(20) ≈ 4.47
        $this->assertAlmostEquals(4.47, $chain(5), 2);
    }

    /**
     * Test with built-in functions
     */
    public function testWithBuiltInFunctions(): void
    {
        $chain = chainCallables([
            'trim',
            'strtoupper',
        ]);

        $this->assertEquals('HELLO', $chain('  hello  '));
    }

    /**
     * Test with built-in and custom functions
     */
    public function testWithMixedFunctions(): void
    {
        $chain = chainCallables([
            'trim',
            'strtoupper',
            fn($str) => str_repeat($str, 2),
        ]);

        $this->assertEquals('HELLOHELLO', $chain('  hello  '));
    }

    /**
     * Test with array callable (object method)
     */
    public function testWithArrayCallable(): void
    {
        $handler = new class {
            public function double($x) {
                return $x * 2;
            }

            public function addTen($x) {
                return $x + 10;
            }
        };

        $chain = chainCallables([
            [$handler, 'double'],
            [$handler, 'addTen'],
        ]);

        $this->assertEquals(20, $chain(5)); // (5*2)+10 = 20
    }

    /**
     * Test with static methods
     */
    public function testWithStaticMethods(): void
    {
        $handler = new class {
            public static function double($x) {
                return $x * 2;
            }

            public static function addTen($x) {
                return $x + 10;
            }
        };

        $className = get_class($handler);

        $chain = chainCallables([
            [$handler, 'double'],
            [$handler, 'addTen'],
        ]);

        $this->assertEquals(20, $chain(5)); // (5*2)+10 = 20
    }

    /**
     * Test with non-existent function returns null
     */
    public function testNonExistentFunctionReturnsNull(): void
    {
        $chain = chainCallables([
            'trim',
            'nonexistent_function_xyz',
        ]);

        $this->assertNull($chain);
    }

    /**
     * Test with non-existent static method returns null
     */
    public function testNonExistentStaticMethodReturnsNull(): void
    {
        $chain = chainCallables([
            'trim',
            'NonExistentClass::method',
        ]);

        $this->assertNull($chain);
    }

    /**
     * Test with invalid array callable returns null
     */
    public function testInvalidArrayCallableReturnsNull(): void
    {
        $chain = chainCallables([
            fn($x) => $x * 2,
            [new stdClass(), 'nonExistentMethod'],
        ]);

        $this->assertNull($chain);
    }

    /**
     * Test data transformation pipeline
     */
    public function testDataTransformationPipeline(): void
    {
        $chain = chainCallables([
            fn($str) => strtolower($str),
            fn($str) => str_replace(' ', '_', $str),
            fn($str) => substr($str, 0, 5),
        ]);

        $this->assertEquals('hello', $chain('HELLO WORLD'));
    }

    /**
     * Test mathematical pipeline
     */
    public function testMathematicalPipeline(): void
    {
        $chain = chainCallables([
            fn($x) => $x ** 2,    // square
            fn($x) => $x + 50,    // add 50
            fn($x) => sqrt($x),   // square root
        ]);

        // (5^2) = 25, 25 + 50 = 75, sqrt(75) ≈ 8.66
        $this->assertAlmostEquals(8.66, $chain(5), 2);
    }

    /**
     * Test with invokable object
     */
    public function testWithInvokableObject(): void
    {
        $doubler = new class {
            public function __invoke($x) {
                return $x * 2;
            }
        };

        $adder = new class {
            public function __invoke($x) {
                return $x + 10;
            }
        };

        $chain = chainCallables([
            $doubler,
            $adder,
        ]);

        $this->assertEquals(20, $chain(5)); // (5*2)+10 = 20
    }

    /**
     * Test with string input through pipeline
     */
    public function testStringPipeline(): void
    {
        $chain = chainCallables([
            'trim',
            'strtoupper',
            fn($str) => "PREFIX_$str",
        ]);

        $this->assertEquals('PREFIX_HELLO', $chain('  hello  '));
    }

    /**
     * Test pipeline preserves intermediate values
     */
    public function testIntermediateValues(): void
    {
        $log = [];

        $chain = chainCallables([
            function($x) use (&$log) {
                $log[] = "step1:$x";
                return $x * 2;
            },
            function($x) use (&$log) {
                $log[] = "step2:$x";
                return $x + 10;
            },
        ]);

        $result = $chain(5);

        $this->assertEquals(20, $result);
        $this->assertEquals(['step1:5', 'step2:10'], $log);
    }

    /**
     * Test with mixed resolvable callables
     */
    public function testMixedResolvableCallables(): void
    {
        $obj = new class {
            public function triple($x) {
                return $x * 3;
            }
        };

        $chain = chainCallables
        ([
            fn($x) => $x + 5,           // Closure
            [$obj, 'triple'],           // Array callable
            'abs',                      // String function
        ]);

        // (5 + 5) = 10, 10 * 3 = 30, abs(30) = 30
        $this->assertEquals(30, $chain(5));
    }

    /**
     * Helper method for approximate equality
     */
    private function assertAlmostEquals(float $expected, float $actual, int $decimals = 2): void
    {
        $this->assertEquals($expected, round($actual, $decimals), '', 0.01);
    }
}