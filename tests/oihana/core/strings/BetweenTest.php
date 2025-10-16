<?php

declare(strict_types=1);

namespace oihana\core\strings;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BetweenTest extends TestCase
{
    public function testBetweenWithString()
    {
        $this->assertEquals('<x>', between('x', '<', '>'));
    }

    public function testBetweenWithArray()
    {
        $this->assertEquals('[a b]', between(['a', 'b'], '[', ']'));
    }

    public function testBetweenWithFlagFalse()
    {
        $this->assertEquals('y', between('y', '"', null, false));
    }

    public function testBetweenWithNullRight()
    {
        $this->assertEquals('"y"', between('y', '"'));
    }

    public function testBetweenWithNullExpression()
    {
        $this->assertEquals('""', between(null, '"'));
    }

    public function testBetweenWithEmptyString()
    {
        $this->assertEquals('""', between('', '"'));
    }

    public function testBetweenWithEmptyArray()
    {
        $this->assertEquals('[]', between([], '[', ']'));
    }

    public function testBetweenWithCustomSeparator()
    {
        $this->assertEquals('[a,b]', between(['a', 'b'], '[', ']', true, ','));
    }

    public function testTrimmingPreservesInternalDelimiters(): void
    {
        $expression = '{id:[0-9]{2}}';
        $expected = '{id:[0-9]{2}}';
        $this->assertEquals($expected, between($expression, '{', '}'));
    }

    public function testTrimmingOnlyAppliesWhenFullyWrapped(): void
    {
        // Ne commence pas par '{', donc ne doit pas être trimé, juste encapsulé.
        $this->assertEquals('{test}}}', between('test}}', '{', '}'));

        // Ne finit pas par '}', donc ne doit pas être trimé, juste encapsulé.
        $this->assertEquals('{{{test}', between('{{test', '{', '}'));
    }

    public function testTrimmingCanBeDisabled(): void
    {
        $this->assertEquals('{{test}}', between('{test}', '{', '}', trim: false));
        $this->assertEquals('<<test>>', between('<test>', '<', '>', trim: false));
    }

    /**
     * @return Generator
     */
    public static function trimmingDataProvider(): Generator
    {
        // Scénario de base
        yield 'unwrapped string' => ['test', '[', ']', '[test]'];

        // Scénarios de correction de bug
        yield 'already wrapped with internal delimiter' => ['{id:[0-9]{2}}', '{', '}', '{id:[0-9]{2}}'];
        yield 'already wrapped with multiple delimiters' => ['<<test>>', '<', '>', '<<test>>'];

        // Scénarios de sécurité
        yield 'partially wrapped right side only' => ['test]', '[', ']', '[test]]'];
        yield 'partially wrapped left side only' => ['[test', '[', ']', '[[test]'];
    }

    #[DataProvider( 'trimmingDataProvider' )]
    public function testGeneralTrimmingBehavior( string $expression, string $left, string $right, string $expected): void
    {
        $this->assertEquals($expected, between($expression, $left, $right));
    }
}
