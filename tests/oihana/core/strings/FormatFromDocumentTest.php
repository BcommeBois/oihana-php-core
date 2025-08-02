<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class FormatFromDocumentTest extends TestCase
{
    public function testBasicReplacement(): void
    {
        $result = formatFromDocument('Hello, {{name}}!', ['name' => 'Alice']);
        $this->assertSame('Hello, Alice!', $result);
    }

    public function testMultiplePlaceholders(): void
    {
        $template = '{{greeting}}, {{name}}!';
        $vars = ['greeting' => 'Hi', 'name' => 'Bob'];
        $result = formatFromDocument($template, $vars);
        $this->assertSame('Hi, Bob!', $result);
    }

    public function testMissingPlaceholderValue(): void
    {
        $result = formatFromDocument('Hello, {{name}} {{lastname}}!', ['name' => 'Alice']);
        $this->assertSame('Hello, Alice !', $result);
    }

    public function testEmptyTemplate(): void
    {
        $result = formatFromDocument('', ['any' => 'value']);
        $this->assertSame('', $result);
    }

    public function testEmptyVars(): void
    {
        $result = formatFromDocument('Hello, {{name}}!', []);
        $this->assertSame('Hello, !', $result);
    }

    public function testCustomDelimiters(): void
    {
        $result = formatFromDocument('[[key]] = value', ['key' => 'x'], '[[', ']]');
        $this->assertSame('x = value', $result);
    }

    public function testNoMatchingPlaceholders(): void
    {
        $result = formatFromDocument('No placeholders here.', ['name' => 'Bob']);
        $this->assertSame('No placeholders here.', $result);
    }

    public function testNumericValueReplacement(): void
    {
        $result = formatFromDocument('You have {{count}} messages.', ['count' => 5]);
        $this->assertSame('You have 5 messages.', $result);
    }

    public function testUnderscoreInKeys(): void
    {
        $result = formatFromDocument('Hello, {{first_name}}!', ['first_name' => 'Alice']);
        $this->assertSame('Hello, Alice!', $result, 'Underscores are not matched unless included in pattern.');
    }

    public function testDashInKeys(): void
    {
        $result = formatFromDocument('User: {{user-id}}', ['user-id' => 42]);
        $this->assertSame('User: 42', $result);
    }

    public function testDotInKeys(): void
    {
        $result = formatFromDocument('Value: {{user.name}}', ['user' => [ 'name' => 'Charlie' ] ]);
        $this->assertSame('Value: Charlie', $result);
    }

    public function testDotNotationNestedArray(): void
    {
        $data = [
            'user' => [
                'address' => [
                    'zip' => '75000'
                ]
            ]
        ];

        $result = formatFromDocument('ZIP: {{user.address.zip}}', $data);
        $this->assertSame('ZIP: 75000', $result);
    }

    public function testCustomPattern()
    {
        $template = 'Hello, <<name>>!';
        $data = ['name' => 'Alice'];
        $pattern = '/<<(.+?)>>/';
        $result = formatFromDocument($template, $data, '<<', '>>', '.', $pattern);
        $this->assertSame('Hello, Alice!', $result);
    }

    public function testPreserveMissingPlaceholder(): void
    {
        $template = 'Hello, {{name}} {{lastname}}!';
        $data = ['name' => 'Alice'];
        $result = formatFromDocument($template, $data, preserveMissing:  true);
        $this->assertSame('Hello, Alice {{lastname}}!', $result, 'Missing placeholder should be preserved.');
    }

    public function testPreserveMissingWithNestedKey(): void
    {
        $template = 'City: {{user.address.city}}';
        $data = ['user' => ['address' => []]];
        $result = formatFromDocument($template, $data, '{{', '}}', '.', null, true);
        $this->assertSame('City: {{user.address.city}}', $result, 'Missing nested placeholder should be preserved.');
    }
}