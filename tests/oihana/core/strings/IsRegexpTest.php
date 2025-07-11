<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class IsRegexpTest extends TestCase
{
    #[DataProvider( 'provideValidRegexps' )]
    public function testValidRegexps( string $pattern): void
    {
        $this->assertTrue(isRegexp($pattern), "Failed asserting that '{$pattern}' is a valid regexp.");
    }

    #[DataProvider( 'provideInvalidRegexps' )]
    public function testInvalidRegexps( string $pattern): void
    {
        $this->assertFalse( isRegexp($pattern) , "Failed asserting that '{$pattern}' is an invalid regexp.");
    }

    /**
     * Provides a set of valid regular expressions.
     */
    public static function provideValidRegexps(): array
    {
        return [
            'empty pattern' => ['//'],
            'simple match' => ['/foo/'],
            'with modifier' => ['/foo/i'],
            'with all modifiers' => ['/foo/imsxuADU'],
            'complex pattern' => ['/^[a-z0-9_-]{3,16}$/'],
            'pattern with escaped chars' => ['/^\/[a-z]\/$/'],
            'pattern with delimiters other than /' => ['#^\d+$#'],
            'pattern with curly braces' => ['~^(.*)$~'],
        ];
    }

    /**
     * Provides a set of invalid regular expressions.
     */
    public static function provideInvalidRegexps(): array
    {
        return [
            'missing starting delimiter' => ['foo/'],
            'missing ending delimiter' => ['/foo'],
            'unmatched parentheses' => ['/(foo/'],
            'invalid modifier' => ['/foo/g'], // g is not a valid modifier in PHP PCRE
            'unterminated character class' => ['/[a-z/'],
            'empty string' => [''],
        ];
    }
}