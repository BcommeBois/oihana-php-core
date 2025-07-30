<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class FormatTest extends TestCase
{
    public function testFormatWithArrayDocument(): void
    {
        $template = 'Hello, {{name}}!';
        $document = ['name' => 'Alice'];
        $expected = 'Hello, Alice!';

        $this->assertSame($expected, format($template, $document));
    }

    public function testFormatWithNestedKeys(): void
    {
        $template = 'ZIP: {{user.address.zip}}';
        $document = ['user' => ['address' => ['zip' => '75000']]];
        $expected = 'ZIP: 75000';

        $this->assertSame($expected, format($template, $document));
    }

    public function testFormatWithCustomDelimiters(): void
    {
        $template = 'Today is [[day]]';
        $document = ['day' => 'Monday'];
        $expected = 'Today is Monday';

        $this->assertSame($expected, format($template, $document, '[[', ']]'));
    }

    public function testFormatWithCustomPattern(): void
    {
        $template = 'Hello, <<name>>!';
        $document = ['name' => 'Alice'];
        $pattern = '/<<(.+?)>>/';
        $expected = 'Hello, Alice!';

        $this->assertSame($expected, format($template, $document, '<<', '>>', '.', $pattern));
    }

    public function testFormatWithStringDocument(): void
    {
        $template = 'User: {{name}}, Role: {{role}}';
        $document = 'anonymous';
        $expected = 'User: anonymous, Role: anonymous';

        $this->assertSame($expected, format($template, $document));
    }

    public function testFormatWithMissingKey(): void
    {
        $template = 'Hello, {{name}}! Your age is {{age}}.';
        $document = ['name' => 'Alice'];
        $expected = 'Hello, Alice! Your age is .'; // missing age replaced by empty string

        $this->assertSame($expected, format($template, $document));
    }
}