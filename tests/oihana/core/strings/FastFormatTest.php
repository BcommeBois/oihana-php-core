<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;
use stdClass;

class FastFormatTest extends TestCase
{
    public function testNullPattern()
    {
        $this->assertEquals("", fastFormat(null));
    }

    public function testEmptyPattern()
    {
        $this->assertEquals("", fastFormat("") );
    }

    public function testNoArgs()
    {
        $this->assertEquals("Hello", fastFormat("Hello"));
    }

    public function testFirstArgIsArray()
    {
        $this->assertEquals("Hello, World!", fastFormat("Hello, {0}!", ["World"]));
    }

    public function testBasicReplacement()
    {
        $this->assertEquals("Hello, World!", fastFormat("Hello, {0}!", "World"));
    }

    public function testMultipleReplacements()
    {
        $this->assertEquals("Hello, Alice! You have 5 messages.", fastFormat("Hello, {0}! You have {1} messages.", "Alice", 5));
    }

    public function testMissingPlaceholder()
    {
        $this->assertEquals("Hello, Alice! You have {1} messages.", fastFormat("Hello, {0}! You have {1} messages.", "Alice"));
    }

    public function testExtraArgs()
    {
        $this->assertEquals("Hello, World!", fastFormat("Hello, {0}!", "World", "Extra"));
    }

    public function testNonStringArgs()
    {
        $this->assertEquals("Value: 42" , fastFormat("Value: {0}", 42    ) ) ;
        $this->assertEquals("Value: 1"  , fastFormat("Value: {0}", true  ) ) ;
        $this->assertEquals("Value: {0}"   , fastFormat("Value: {0}", null  ) ) ;
    }

    public function testNonNumericPlaceholders()
    {
        $this->assertEquals("Hello, {name}!", fastFormat("Hello, {name}!", "World"));
    }

    public function testNoPlaceholders()
    {
        $this->assertEquals("Hello, world!", fastFormat("Hello, world!"));
    }

    public function testEmptyArrayArg()
    {
        $this->assertEquals("Hello, {0}!", fastFormat("Hello, {0}!", []));
    }

    public function testMultiplePlaceholders()
    {
        $this->assertEquals("a b c", fastFormat("{0} {1} {2}", "a", "b", "c"));
    }

    public function testNonSequentialPlaceholders()
    {
        $this->assertEquals("c b a", fastFormat("{2} {1} {0}", "a", "b", "c"));
    }

    public function testRepeatedPlaceholders()
    {
        $this->assertEquals("a a b", fastFormat("{0} {0} {1}", "a", "b"));
    }

    public function testPlaceholdersNoArgs()
    {
        $this->assertEquals("Hello, {0}!", fastFormat("Hello, {0}!"));
    }

    public function testPlaceholdersEmptyArrayArg()
    {
        $this->assertEquals("Hello, {0}!", fastFormat("Hello, {0}!", []));
    }

    public function testComplexPattern()
    {
        $pattern = "{0} {2} {1} {3} {0}";
        $args = ["a", "b", "c", "d"];
        $expected = "a c b d a";
        $this->assertEquals($expected, fastFormat($pattern, ...$args));
    }

    public function testLargeIndex()
    {
        $pattern = "{99} test {0}";
        $args = ["value", "unused"];
        $expected = "{99} test value";
        $this->assertEquals($expected, fastFormat($pattern, ...$args));
    }

    public function testNegativeIndex()
    {
        $pattern = "{-1} test {0}";
        $args = ["value"];
        $expected = "{-1} test value";
        $this->assertEquals($expected, fastFormat($pattern, ...$args));
    }

    public function testNonIntegerIndex()
    {
        $pattern = "{a} test {0}";
        $args = ["value"];
        $expected = "{a} test value";
        $this->assertEquals($expected, fastFormat($pattern, ...$args));
    }

    public function testArrayWithGaps()
    {
        $args = [0 => "a", 2 => "c"]; // index 1 is missing
        $pattern = "{0} {1} {2}";
        $expected = "a {1} c";
        $this->assertEquals($expected, fastFormat($pattern, $args));
    }

    public function testInstanceArgument()
    {
        $obj = new class()
        {
            public function __toString()
            {
                return "object";
            }
        };
        $expected = "Value: object";
        $this->assertEquals($expected, fastFormat("Value: {0}", [ $obj ] ) );
    }

    public function testObjectArgument()
    {
        $obj = new stdClass();
        $expected = "Value: [object stdClass]";
        $this->assertEquals($expected, fastFormat("Value: {0}", [ $obj ] ) );
    }

    public function testObjectArgumentWithCustomToString()
    {
        $obj = new stdClass();
        $obj->__toString = function() { return "object"; };
        $expected = "Value: object";
        $this->assertEquals($expected, fastFormat("Value: {0}", [ $obj ] ) );
    }
}