<?php

namespace tests\oihana\core\callables;

use PHPUnit\Framework\TestCase;
use function oihana\core\callables\isCallable;
use function oihana\core\callables\resolveCallable;

final class IsCallableTest extends TestCase
{
    /**
     * Test isCallable with built-in functions
     */
    public function testIsCallableBuiltinFunctions()
    {
        $this->assertTrue( isCallable('strlen'));
        $this->assertTrue( isCallable('array_map'));
        $this->assertTrue( isCallable('implode'));
        $this->assertTrue( isCallable('count'));
        $this->assertTrue( isCallable('json_encode'));
    }

    /**
     * Test isCallable with non-existent functions
     */
    public function testIsCallableNonExistentFunctions()
    {
        $this->assertFalse( isCallable('nonexistent_function'));
        $this->assertFalse( isCallable('fake_function_xyz'));
        $this->assertFalse( isCallable('does_not_exist'));
    }

    /**
     * Test isCallable with valid static methods
     */
    public function testIsCallableValidStaticMethods()
    {
        $this->assertTrue( isCallable(self::class . '::staticHelper'));
    }

    /**
     * Test isCallable with invalid static methods
     */
    public function testIsCallableInvalidStaticMethods()
    {
        $this->assertFalse( isCallable('NonExistentClass::method'));
        $this->assertFalse( isCallable(self::class . '::nonExistentMethod'));
        $this->assertFalse( isCallable('DateTime::fakeMethod'));
    }

    /**
     * Test isCallable with namespaced functions
     */
    public function testIsCallableNamespacedFunctions()
    {
        $this->assertTrue  ( isCallable('oihana\core\callables\resolveCallable'));
        $this->assertTrue  ( isCallable('oihana\core\callables\isCallable'));
        $this->assertFalse ( isCallable('oihana\core\callables\nonexistent'));
    }

    /**
     * Test isCallable with empty string
     */
    public function testIsCallableEmptyString()
    {
        $this->assertFalse( isCallable(''));
    }

    /**
     * Test isCallable with leading backslash
     */
    public function testIsCallableLeadingBackslash()
    {
        $this->assertTrue(isCallable('\strlen'));
        $this->assertTrue(isCallable('\array_map'));
        $this->assertFalse(isCallable('\nonexistent'));
    }

    /**
     * Test isCallable returns boolean
     */
    public function testIsCallableReturnsBoolean()
    {
        $result1 = isCallable('strlen');
        $result2 = isCallable('fake_function');

        $this->assertIsBool($result1);
        $this->assertIsBool($result2);
        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }

    /**
     * Test isCallable consistency with resolveCallable
     */
    public function testIsCallableConsistencyWithResolveCallable()
    {
        $testCases = [
            'strlen',
            'array_filter',
            'DateTime::createFromFormat',
            'NonExistent::method',
            'fake_function',
            self::class . '::staticHelper',
            'oihana\core\callables\resolveCallable',
        ];

        foreach ($testCases as $callable)
        {
            $isCallableResult      = isCallable($callable) ;
            $resolveCallableResult = resolveCallable($callable) ;

            // isCallable should be true iff resolveCallable returns non-null
            $this->assertSame
            (
                $isCallableResult,
                $resolveCallableResult !== null,
                "Mismatch for callable: $callable"
            );
        }
    }

    /**
     * Test isCallable with case variations (PHP functions are case-insensitive)
     */
    public function testIsCallableCaseInsensitivity()
    {
        $this->assertTrue(isCallable('strlen'));
        $this->assertTrue(isCallable('STRLEN'));
        $this->assertTrue(isCallable('StrLen'));
        $this->assertTrue(isCallable('array_map'));
        $this->assertTrue(isCallable('ARRAY_MAP'));
    }

    /**
     * Test isCallable for validation in conditional logic
     */
    public function testIsCallableInConditionalLogic()
    {
        $validCallables = [
            'strlen',
            'array_map',
            'DateTime::createFromFormat',
            'oihana\core\callables\isCallable',
        ];

        $invalidCallables = [
            'nonexistent',
            'Fake::method',
            'missing_function',
            '',
        ];

        foreach ($validCallables as $callable) {
            $this->assertTrue(
                isCallable($callable),
                "Expected '$callable' to be callable"
            );
        }

        foreach ($invalidCallables as $callable) {
            $this->assertFalse(
                isCallable($callable),
                "Expected '$callable' to NOT be callable"
            );
        }
    }

    /**
     * Test isCallable with special characters
     */
    public function testIsCallableSpecialCharacters()
    {
        $this->assertFalse(isCallable('function-name'));
        $this->assertFalse(isCallable('function name'));
        $this->assertFalse(isCallable('function@name'));
        $this->assertFalse(isCallable('function#name'));
    }

    /**
     * Test isCallable multiple calls return consistent results
     */
    public function testIsCallableConsistentResults()
    {
        $callable = 'strlen';

        $result1 = isCallable($callable);
        $result2 = isCallable($callable);
        $result3 = isCallable($callable);

        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertTrue($result3);
        $this->assertSame($result1, $result2);
        $this->assertSame($result2, $result3);
    }

    /**
     * Test isCallable short-circuit evaluation
     */
    public function testIsCallableShortCircuit()
    {
        // Valid callable should return true immediately
        if (isCallable('strlen')) {
            $this->assertTrue(true);
        } else {
            $this->fail('strlen should be callable');
        }

        // Invalid callable should return false immediately
        if (!isCallable('fake_function')) {
            $this->assertTrue(true);
        } else {
            $this->fail('fake_function should not be callable');
        }
    }

    /**
     * Helper: static method for testing
     */
    public static function staticHelper(string $value): string
    {
        return strtoupper($value);
    }
}