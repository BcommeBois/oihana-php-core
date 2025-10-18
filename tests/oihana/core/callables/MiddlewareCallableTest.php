<?php

namespace tests\oihana\core\callables;

use PHPUnit\Framework\TestCase;
use function oihana\core\callables\middlewareCallable;

final class MiddlewareCallableTest extends TestCase
{
    public function testBeforeAfterSingle(): void
    {
        $log = [];

        $fn = fn(int $x): int => $x * 2;

        $wrapped = middlewareCallable
        (
            $fn,
            before: function(int $x) use (&$log) { $log[] = "before-$x"; },
            after: function(int $x, int $r) use (&$log) { $log[] = "after-$r"; }
        );

        $result = $wrapped(5);

        $this->assertSame(10, $result);
        $this->assertSame(['before-5', 'after-10'], $log);
    }

    public function testBeforeAfterMultiple(): void
    {
        $log = [];

        $fn = fn(int $x): int => $x + 1;

        $wrapped = middlewareCallable
        (
            $fn,
            before: [
                function(int $x) use (&$log) { $log[] = "B1-$x"; },
                function(int $x) use (&$log) { $log[] = "B2-$x"; },
            ],
            after: [
                function(int $x, int $r) use (&$log) { $log[] = "A1-$r"; },
                function(int $x, int $r) use (&$log) { $log[] = "A2-$r"; },
            ]
        );

        $result = $wrapped(3);

        $this->assertSame(4, $result);
        $this->assertSame(['B1-3','B2-3','A1-4','A2-4'], $log);
    }

    public function testAfterCanModifyResult(): void
    {
        $fn = fn(int $x): int => $x + 2;

        $wrapped = middlewareCallable(
            $fn,
            after: fn(int $x, int $r) => $r * 10
        );

        $result = $wrapped(3);

        $this->assertSame(50, $result); // (3+2)*10
    }

    public function testVariadicArguments(): void
    {
        $log = [];

        $fn = fn(int ...$nums): int => array_sum($nums);

        $wrapped = middlewareCallable(
            $fn,
            before : function(...$args) use (&$log) { $log[] = "before-" . implode(',', $args); },
            after  : function(...$args) use (&$log) { $log[] = "after-" . end($args); }
        );

        $result = $wrapped(1, 2, 3);

        $this->assertSame(6, $result);
        $this->assertSame(['before-1,2,3','after-6'], $log);
    }

    public function testBeforeAfterWithNullAndFalse(): void
    {
        $log = [];

        $fn = fn(bool $flag = true): ?bool => $flag ? null : false;

        $wrapped = middlewareCallable
        (
            $fn,
            before : function($v) use (&$log) { $log[] = "before-$v"; },
            after  : function($v, $r) use (&$log) { $log[] = "after-" . var_export($r, true); }
        );

        $r1 = $wrapped(true);  // returns null
        $r2 = $wrapped(false); // returns false

        $this->assertNull($r1);
        $this->assertFalse($r2);
        $this->assertSame
        ([
            'before-1','after-NULL',
            'before-','after-false'
        ], $log);
    }
}