<?php

namespace tests\oihana\core\date;

use function oihana\core\date\hasLiteralZulu;

use oihana\core\date\DateFormat;
use PHPUnit\Framework\TestCase;

final class HasLiteralZuluTest extends TestCase
{
    /**
     * An escaped `Z` is the ISO-8601 Zulu designator.
     * @return void
     */
    public function testEscapedZIsALiteralZulu(): void
    {
        $this->assertTrue( hasLiteralZulu( '\Z' ) );
        $this->assertTrue( hasLiteralZulu( 'Y-m-d\TH:i:s\Z' ) );
        $this->assertTrue( hasLiteralZulu( 'Y-m-d\TH:i:s.v\Z' ) );
        $this->assertTrue( hasLiteralZulu( 'Ymd\THis\Z' ) );
        $this->assertTrue( hasLiteralZulu( DateFormat::DEFAULT ) );
        $this->assertTrue( hasLiteralZulu( DateFormat::ZULU_MILLI ) );
    }

    /**
     * The designator does not have to close the pattern.
     * @return void
     */
    public function testALiteralZuluIsFoundAnywhereInThePattern(): void
    {
        $this->assertTrue( hasLiteralZulu( '\Z Y-m-d' ) );
        $this->assertTrue( hasLiteralZulu( 'Y-m-d \Z H:i' ) );
    }

    /**
     * A bare `Z` is the native token for the timezone offset in seconds, not a designator.
     * @return void
     */
    public function testUnescapedZIsTheNativeOffsetToken(): void
    {
        $this->assertFalse( hasLiteralZulu( 'Z' ) );
        $this->assertFalse( hasLiteralZulu( 'Y-m-d\TH:i:sZ' ) );
    }

    /**
     * `\\Z` is an escaped backslash followed by that same native token.
     * @return void
     */
    public function testEscapedBackslashBeforeZIsNotALiteralZulu(): void
    {
        $this->assertFalse( hasLiteralZulu( '\\\\Z' ) );
        $this->assertFalse( hasLiteralZulu( 'Y-m-d\\\\Z' ) );
    }

    /**
     * A third backslash escapes the `Z` again.
     * @return void
     */
    public function testAnOddNumberOfBackslashesBeforeZIsALiteralZulu(): void
    {
        $this->assertTrue( hasLiteralZulu( '\\\\\Z' ) );
    }

    /**
     * A format carrying a real offset token says its own timezone.
     * @return void
     */
    public function testOffsetBearingPatternsAreNotZulu(): void
    {
        $this->assertFalse( hasLiteralZulu( 'Y-m-d\TH:i:sP' ) );
        $this->assertFalse( hasLiteralZulu( 'Y-m-d\TH:i:s.vP' ) );
        $this->assertFalse( hasLiteralZulu( 'Ymd\THisO' ) );
        $this->assertFalse( hasLiteralZulu( 'Y-m-d\TH:i:sT' ) );
    }

    /**
     * Patterns without any timezone information at all.
     * @return void
     */
    public function testPatternsWithoutTimezoneInformation(): void
    {
        $this->assertFalse( hasLiteralZulu( '' ) );
        $this->assertFalse( hasLiteralZulu( 'Y-m-d' ) );
        $this->assertFalse( hasLiteralZulu( 'Y-m-d\TH:i:s' ) );
        $this->assertFalse( hasLiteralZulu( 'd/m/Y H:i' ) );
    }

    /**
     * A trailing backslash escapes nothing and must not read past the end of the pattern.
     * @return void
     */
    public function testTrailingBackslashDoesNotOverrun(): void
    {
        $this->assertFalse( hasLiteralZulu( '\\' ) );
        $this->assertFalse( hasLiteralZulu( 'Y-m-d\\' ) );
    }

    /**
     * The lowercase `z` (day of the year) is a different token entirely.
     * @return void
     */
    public function testLowercaseZIsNotAZuluDesignator(): void
    {
        $this->assertFalse( hasLiteralZulu( '\z' ) );
        $this->assertFalse( hasLiteralZulu( 'Y-z' ) );
    }
}
