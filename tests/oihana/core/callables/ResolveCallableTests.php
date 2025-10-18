<?php

namespace oihana\core\callables;

use PHPUnit\Framework\TestCase;

final class ResolveCallableTests extends TestCase
{
    /**
     * Test resolving a built-in function
     */
    public function testResolveBuiltinFunction()
    {
        $callable = resolveCallable('strlen');

        $this->assertIsCallable($callable);
        $this->assertSame(5, $callable('hello'));
    }

    /**
     * Test resolving a function with namespace
     */
    public function testResolveFunctionWithNamespace()
    {
        $callable = resolveCallable('oihana\core\callables\resolveCallable');

        $this->assertIsCallable($callable);
        $this->assertNull($callable('nonexistent\function'));
    }

    /**
     * Test resolving a non-existent function returns null
     */
    public function testResolveNonExistentFunction()
    {
        $callable = resolveCallable('nonexistent\function');

        $this->assertNull($callable);
    }

    /**
     * Test resolving static method in current class
     */
    public function testResolveStaticMethod()
    {
        $callable = resolveCallable(self::class . '::staticTransform');

        $this->assertIsCallable($callable);
        $this->assertSame('HELLO', $callable('hello'));
    }

    /**
     * Test resolving non-existent static method returns null
     */
    public function testResolveNonExistentStaticMethod()
    {
        $callable = resolveCallable(self::class . '::nonExistentMethod');

        $this->assertNull($callable);
    }

    /**
     * Test resolving fully qualified static method
     */
    public function testResolveFullyQualifiedStaticMethod()
    {
        $callable = resolveCallable(__CLASS__ . '::staticTransform');

        $this->assertIsCallable($callable);
        $this->assertSame('TEST', $callable('test'));
    }

    /**
     * Test with array_map integration
     */
    public function testResolveCallableWithArrayMap()
    {
        $callable = resolveCallable('strlen');

        $this->assertIsCallable($callable);
        $result = array_map($callable, ['hello', 'world', 'php']);
        $this->assertSame([5, 5, 3], $result);
    }

    /**
     * Test resolving function with backslash prefix
     */
    public function testResolveFunctionWithLeadingBackslash()
    {
        $callable = resolveCallable('\strlen');

        $this->assertIsCallable($callable);
        $this->assertSame(3, $callable('abc'));
    }

    /**
     * Test resolving method-like string with invalid class returns null
     */
    public function testResolveInvalidClassStaticMethod()
    {
        $callable = resolveCallable('NonExistentClass::method');

        $this->assertNull($callable);
    }

    /**
     * Test resolving empty string returns null
     */
    public function testResolveEmptyString()
    {
        $callable = resolveCallable('');

        $this->assertNull($callable);
    }

    /**
     * Test case sensitivity
     */
    public function testResolveCaseSensitivity()
    {
        // PHP functions are case-insensitive
        $callable1 = resolveCallable('strlen');
        $callable2 = resolveCallable('STRLEN');
        $callable3 = resolveCallable('StrLen');

        $this->assertIsCallable($callable1);
        $this->assertIsCallable($callable2);
        $this->assertIsCallable($callable3);
    }

    /**
     * Test with built-in class static method
     */
    public function testResolveBuiltinClassStaticMethod()
    {
        $callable = resolveCallable('Exception::getCode');

        // Exception::getCode is not a static method, should return null
        $this->assertNull($callable);
    }

    /**
     * Test resolve with DateTime::createFromFormat (actual static method)
     */
    public function testResolveRealStaticMethod()
    {
        $callable = resolveCallable('DateTime::createFromFormat');

        $this->assertIsCallable($callable);
        $result = $callable('Y-m-d', '2024-01-15');
        $this->assertInstanceOf(\DateTime::class, $result);
    }

    /**
     * Test resolve multiple times returns consistent result
     */
    public function testResolveSameFunctionMultipleTimes()
    {
        $callable1 = resolveCallable('strlen');
        $callable2 = resolveCallable('strlen');

        $this->assertIsCallable($callable1);
        $this->assertIsCallable($callable2);
        $this->assertSame($callable1('test'), $callable2('test'));
    }

    /**
     * Helper: static method for testing
     */
    public static function staticTransform(string $value): string
    {
        return strtoupper($value);
    }
}