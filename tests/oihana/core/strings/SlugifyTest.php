<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\slugify;

use PHPUnit\Framework\TestCase;

class SlugifyTest extends TestCase
{
    public function testBasicSlug() : void
    {
        $this->assertSame( 'hello-world' , slugify( 'Héllo, World!' ) ) ;
    }

    public function testTransliteratesAndTrims() : void
    {
        $this->assertSame( 'cafe-munsterlander' , slugify( '  Café Münsterländer  ' ) ) ;
    }

    public function testCustomSeparator() : void
    {
        $this->assertSame( 'foo_bar_baz' , slugify( 'Foo_Bar Baz' , '_' ) ) ;
    }

    public function testCollapsesRunsOfSeparators() : void
    {
        $this->assertSame( 'a-b-c' , slugify( 'a---b   c' ) ) ;
    }

    public function testNonLatinReturnsEmpty() : void
    {
        $this->assertSame( '' , slugify( '日本語' ) ) ;
    }

    public function testEmptyString() : void
    {
        $this->assertSame( '' , slugify( '' ) ) ;
    }
}
