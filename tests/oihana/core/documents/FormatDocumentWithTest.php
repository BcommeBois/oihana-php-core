<?php

namespace oihana\core\documents ;

use oihana\core\documents\mocks\MockFormatDocument;
use PHPUnit\Framework\TestCase;
use stdClass;

class FormatDocumentWithTest extends TestCase
{
    public function testSimpleArrayFormatting(): void
    {
        $source = ['dir' => '/var/www'];
        $target = ['htdocs' => '{{dir}}/htdocs'];

        $result = formatDocumentWith($target, $source);

        $this->assertSame('/var/www/htdocs', $result['htdocs']);
    }

    public function testNestedArrayFormatting(): void
    {
        $source = [
            'dir' => '/var/www',
            'site' => ['domain' => 'example.com']
        ];
        $target = [
            'htdocs' => '{{dir}}/htdocs',
            'url' => 'https://{{site.domain}}'
        ];

        $result = formatDocumentWith($target, $source);

        $this->assertSame('/var/www/htdocs', $result['htdocs']);
        $this->assertSame('https://example.com', $result['url']);
    }

    public function testObjectFormatting(): void
    {
        $source = (object)['name' => 'Alice'];
        $target = (object)['greeting' => 'Hello {{name}}'];

        $result = formatDocumentWith($target, $source);

        $this->assertInstanceOf(stdClass::class, $result);
        $this->assertSame('Hello Alice', $result->greeting);
    }

    public function testCustomClassFormatting(): void
    {
        $source = ['name' => 'World'];
        $target = new MockFormatDocument('World', 'Hello {{name}}');

        $result = formatDocumentWith($target, $source);

        $this->assertInstanceOf(MockFormatDocument::class, $result);
        $this->assertSame('Hello World', $result->greeting);
    }

    public function testPreserveMissingPlaceholdersFalse(): void
    {
        $source = ['name' => 'Alice'];
        $target = ['greeting' => 'Hi {{name}}, welcome to {{app}}'];

        $result = formatDocumentWith($target, $source, preserveMissing: false);

        $this->assertSame('Hi Alice, welcome to ', $result['greeting']);
    }

    public function testPreserveMissingPlaceholdersTrue(): void
    {
        $source = ['name' => 'Alice'];
        $target = ['greeting' => 'Hi {{name}}, welcome to {{app}}'];

        $result = formatDocumentWith($target, $source, preserveMissing: true);

        $this->assertSame('Hi Alice, welcome to {{app}}', $result['greeting']);
    }

    public function testNestedPlaceholders(): void
    {
        $source = [
            'env' => 'prod',
            'config' => [
                'prod' => ['url' => 'https://example.com']
            ]
        ];

        $target = [
            'api' => '{{config.{{env}}.url}}/api'
        ];

        $result = formatDocumentWith($target, $source);

        $this->assertSame('https://example.com/api', $result['api']);
    }

    public function testFormatterCallback(): void
    {
        $source = ['version' => '1.0'];
        $target = ['msg' => 'App version: [[version]]'];

        $formatter = function ($value, $src, $prefix, $suffix) {
            return str_replace('[[version]]', $src['version'], $value);
        };

        $result = formatDocumentWith($target, $source, '[[', ']]', formatter: $formatter);

        $this->assertSame('App version: 1.0', $result['msg']);
    }

    public function testCircularReferenceHandling(): void
    {
        $a = new stdClass();
        $b = new stdClass();

        $a->name = 'Test';
        $a->ref = $b;
        $b->ref = $a;
        $b->msg = 'Hello {{name}}';

        $result = formatDocumentWith($a, $a);

        $this->assertSame('Hello Test', $result->ref->msg);
    }

    public function testEmptyAndScalarValuesPreserved(): void
    {
        $source = ['name' => 'Alice'];
        $target = [
            'null' => null,
            'bool' => true,
            'int' => 42,
            'str' => 'Hi {{name}}',
            'empty' => ''
        ];

        $result = formatDocumentWith($target, $source);

        $this->assertNull($result['null']);
        $this->assertTrue($result['bool']);
        $this->assertSame(42, $result['int']);
        $this->assertSame('Hi Alice', $result['str']);
        $this->assertSame('', $result['empty']);
    }
}