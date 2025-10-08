<?php declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

class BetweenParenthesesTest extends TestCase
{
    public function testBetweenParentheses(): void
    {
        // Test with a simple string
        $this->assertEquals('(hello)', betweenParentheses('hello'));

        // Test with parentheses disabled
        $this->assertEquals('hello', betweenParentheses('hello', false));

        // Test with an array
        $this->assertEquals('(a b c)', betweenParentheses(['a', 'b', 'c']));

        // Test with an array and double quotes
        $this->assertEquals('(a b "c")', betweenParentheses(['a', 'b', '"c"']));
        $this->assertEquals('(a,b,"c")', betweenParentheses(['a', 'b', '"c"'], separator: ',', trim: false));

        // Test with an array and custom separator
        $this->assertEquals('(a-b-c)', betweenParentheses(['a', 'b', 'c'], true, '-'));

        // Test with an empty string
        $this->assertEquals('()', betweenParentheses(''));

        // Test with a null expression
        $this->assertEquals('()', betweenParentheses(null));
    }
}
