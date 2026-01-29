<?php

namespace tests\oihana\core\arrays;

use PHPUnit\Framework\TestCase;
use function oihana\core\arrays\isCallableWithParams;

/**
 * Tests for isCallableWithParams() function.
 */
class IsCallableWithParamsTest extends TestCase
{
    // ========================================
    // BASIC DETECTION (without validation)
    // ========================================

    public function testDetectsCallableWithNumericParams(): void
    {
        $this->assertTrue( isCallableWithParams( ['substring', 0, 3] ) );
        $this->assertTrue( isCallableWithParams( ['pow', 2] ) );
        $this->assertTrue( isCallableWithParams( ['round', 2] ) );
    }

    public function testDetectsCallableWithStringParams(): void
    {
        $this->assertTrue( isCallableWithParams( ['concat', 'hello', 'world'] ) );
        $this->assertTrue( isCallableWithParams( ['replace', 'old', 'new'] ) );
    }

    public function testDetectsCallableWithMixedParams(): void
    {
        $this->assertTrue( isCallableWithParams( ['slice', 0, 'end'] ) );
        $this->assertTrue( isCallableWithParams( ['cache', 'redis', 3600, true] ) );
    }

    // ========================================
    // DETECTION WITH VALIDATION
    // ========================================

    public function testDetectsValidCallableWithParamsWhenValidationEnabled(): void
    {
        $validNames = ['trim', 'lower', 'upper', 'substring'];

        $this->assertTrue( isCallableWithParams( ['substring', 0, 3] , $validNames ) );
        $this->assertTrue( isCallableWithParams( ['trim', 1] , $validNames ) );
        $this->assertTrue( isCallableWithParams( ['upper', 'arg'] , $validNames ) );
    }

    public function testRejectsInvalidCallableNameWhenValidationEnabled(): void
    {
        $validNames = ['trim', 'lower', 'upper'];

        $this->assertFalse( isCallableWithParams( ['substring', 0, 3] , $validNames ) );
        $this->assertFalse( isCallableWithParams( ['unknown', 1, 2] , $validNames ) );
        $this->assertFalse( isCallableWithParams( ['notInList', 'param'] , $validNames ) );
    }

    // ========================================
    // FUNCTION CHAIN DETECTION
    // ========================================

    public function testRejectsChainOfTwoValidCallables(): void
    {
        $validNames = ['trim', 'lower', 'upper'];

        $this->assertFalse( isCallableWithParams( ['trim', 'lower'] , $validNames ) );
        $this->assertFalse( isCallableWithParams( ['upper', 'trim'] , $validNames ) );
        $this->assertFalse( isCallableWithParams( ['lower', 'upper'] , $validNames ) );
    }

    public function testRejectsChainWithExplicitFormat(): void
    {
        $validNames = ['trim', 'lower', 'upper'];

        $this->assertFalse( isCallableWithParams( [['trim', 1], 'lower'] , $validNames ) );
        $this->assertFalse( isCallableWithParams( [['upper'], 'trim'] , $validNames ) );
    }

    public function testAcceptsCallableWithStringParamThatIsNotValidCallable(): void
    {
        $validNames = ['trim', 'lower', 'upper'];

        // 'hello' is not a valid callable, so it's a parameter
        $this->assertTrue( isCallableWithParams( ['trim', 'hello'] , $validNames ) );
        $this->assertTrue( isCallableWithParams( ['lower', 'someString'] , $validNames ) );
    }

    // ========================================
    // EDGE CASES
    // ========================================

    public function testRejectsEmptyArray(): void
    {
        $this->assertFalse( isCallableWithParams( [] ) );
        $this->assertFalse( isCallableWithParams( [] , ['trim'] ) );
    }

    public function testRejectsSingleElementArray(): void
    {
        $this->assertFalse( isCallableWithParams( ['lower'] ) );
        $this->assertFalse( isCallableWithParams( ['trim'] , ['trim', 'lower'] ) );
    }

    public function testRejectsArrayWithNonStringFirstElement(): void
    {
        $this->assertFalse( isCallableWithParams( [123, 'param'] ) );
        $this->assertFalse( isCallableWithParams( [null, 'param'] ) );
        $this->assertFalse( isCallableWithParams( [['nested'], 'param'] ) );
        $this->assertFalse( isCallableWithParams( [true, 'param'] ) );
    }

    public function testRejectsWhenSecondElementIsArray(): void
    {
        $this->assertFalse( isCallableWithParams( ['trim', ['nested']] ) );
        $this->assertFalse( isCallableWithParams( ['lower', [1, 2, 3]] ) );
        $this->assertFalse( isCallableWithParams( ['func', []] , ['func'] ) );
    }

    // ========================================
    // VALIDATION EDGE CASES
    // ========================================

    public function testAcceptsAnyCallableNameWhenValidationDisabled(): void
    {
        $this->assertTrue( isCallableWithParams( ['anyFunction', 'param'] ) );
        $this->assertTrue( isCallableWithParams( ['unknownMethod', 1, 2, 3] ) );
        $this->assertTrue( isCallableWithParams( ['customFunc', true] ) );
    }

    public function testHandlesEmptyValidationList(): void
    {
        // Empty validation list = no valid names
        $this->assertFalse( isCallableWithParams( ['trim', 1] , [] ) );
        $this->assertFalse( isCallableWithParams( ['any', 'param'] , [] ) );
    }

    public function testIsCaseSensitiveInValidation(): void
    {
        $validNames = ['trim', 'lower'];

        $this->assertTrue( isCallableWithParams( ['trim', 1] , $validNames ) );
        $this->assertFalse( isCallableWithParams( ['TRIM', 1] , $validNames ) );
        $this->assertFalse( isCallableWithParams( ['Trim', 1] , $validNames ) );
    }

    // ========================================
    // MULTIPLE PARAMETERS
    // ========================================

    public function testAcceptsCallableWithManyParameters(): void
    {
        $this->assertTrue( isCallableWithParams( ['func', 1, 2, 3, 4, 5] ) );
        $this->assertTrue( isCallableWithParams( ['method', 'a', 'b', 'c', 'd'] ) );

        $validNames = ['myFunc'];
        $this->assertTrue( isCallableWithParams( ['myFunc', 1, 2, 3] , $validNames ) );
    }

    // ========================================
    // REAL-WORLD SCENARIOS
    // ========================================

    public function testWorksWithFilterFunctions(): void
    {
        $filterFunctions = ['substring', 'trim', 'lower', 'upper', 'pow', 'round'];

        // Valid callable with params
        $this->assertTrue( isCallableWithParams( ['substring', 0, 3] , $filterFunctions ) );
        $this->assertTrue( isCallableWithParams( ['pow', 2] , $filterFunctions ) );

        // Function chain
        $this->assertFalse( isCallableWithParams( ['trim', 'lower'] , $filterFunctions ) );

        // Single function without params
        $this->assertFalse( isCallableWithParams( ['upper'] , $filterFunctions ) );
    }

    public function testWorksWithCacheConfiguration(): void
    {
        $cacheDrivers = ['redis', 'memcached', 'file'];

        $this->assertTrue( isCallableWithParams( ['redis', 'localhost', 6379] , $cacheDrivers ) );
        $this->assertTrue( isCallableWithParams( ['file', '/tmp/cache'] , $cacheDrivers ) );

        $this->assertFalse( isCallableWithParams( ['unknown', 'param'] , $cacheDrivers ) );
    }

    public function testWorksWithEventDispatcher(): void
    {
        $allowedEvents = ['notify', 'log', 'alert'];

        $this->assertTrue( isCallableWithParams( ['notify', 'user@example.com', 'Welcome!'] , $allowedEvents ) );
        $this->assertTrue( isCallableWithParams( ['log', 'error', 'Something went wrong'] , $allowedEvents ) );

        // Chain of events (not supported)
        $this->assertFalse( isCallableWithParams( ['notify', 'log'] , $allowedEvents ) );
    }

    // ========================================
    // BOUNDARY CONDITIONS
    // ========================================

    public function testHandlesExactlyTwoElements(): void
    {
        // Minimum valid case
        $this->assertTrue( isCallableWithParams( ['func', 'param'] ) );
        $this->assertTrue( isCallableWithParams( ['method', 123] ) );

        $validNames = ['func'];
        $this->assertTrue( isCallableWithParams( ['func', 'param'] , $validNames ) );
    }

    public function testHandlesSpecialCharactersInCallableName(): void
    {
        $this->assertTrue( isCallableWithParams( ['trim_whitespace', 'param'] ) );
        $this->assertTrue( isCallableWithParams( ['user:notify', 'email'] ) );
        $this->assertTrue( isCallableWithParams( ['cache.get', 'key'] ) );
    }

    public function testHandlesNullAndBooleanParameters(): void
    {
        $this->assertTrue( isCallableWithParams( ['func', null] ) );
        $this->assertTrue( isCallableWithParams( ['method', true] ) );
        $this->assertTrue( isCallableWithParams( ['call', false, null] ) );
    }

    // ========================================
    // TYPE SAFETY
    // ========================================

    public function testMaintainsTypeStrictnessInValidation(): void
    {
        $validNames = ['123', 'true', 'false'];

        // These are strings in validNames
        $this->assertTrue( isCallableWithParams( ['123', 'param'] , $validNames ) );
        $this->assertTrue( isCallableWithParams( ['true', 'param'] , $validNames ) );

        // These are not (different types)
        $this->assertFalse( isCallableWithParams( [123, 'param'] , $validNames ) );
    }
}