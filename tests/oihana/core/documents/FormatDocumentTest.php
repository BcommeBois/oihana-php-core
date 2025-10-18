<?php

namespace oihana\core\documents ;

use tests\oihana\core\documents\mocks\MockFormatDocument;
use PHPUnit\Framework\TestCase;
use stdClass;

class FormatDocumentTest extends TestCase
{
    public function testFormatDocumentWithNestedPlaceholders(): void
    {
        $config =
        [
            'dir'       => '/var/www/project',
            'htdocs'    => '{{dir}}/htdocs',
            'wordpress' =>
            [
                'server' =>
                [
                    'subdomain' => 'www',
                    'domain'    => 'example.com',
                    'url'       => 'https://{{wordpress.server.subdomain}}.{{wordpress.server.domain}}/'
                ]
            ] ,
            'other' =>
            [
                'welcome' => 'Welcome to {{wordpress.server.domain}}!',
                'empty' => '',
                'number' => 42
            ]
        ];

        $formatted = formatDocument( $config ) ;

        // Strings with placeholders replaced
        $this->assertSame('/var/www/project/htdocs'  , $formatted['htdocs'] );
        $this->assertSame('https://www.example.com/' , $formatted['wordpress']['server']['url'] );
        $this->assertSame('Welcome to example.com!'  , $formatted['other']['welcome'] );

        // Non-string values remain unchanged
        $this->assertSame('', $formatted['other']['empty']);
        $this->assertSame(42, $formatted['other']['number']);

        // Original document is not modified
        $this->assertSame('{{dir}}/htdocs', $config['htdocs']);
    }

    public function testFormatDocumentWithAnonymousClass(): void
    {
        $obj = new class
        {
            public string $name = 'Hello';
            public string $greeting = '{{name}}, World!';
        };

        $result = formatDocument($obj);

        $this->assertSame('Hello, World!', $result->greeting);
        $this->assertEquals( 'stdClass' , get_class($result), 'Object class should be preserved');
    }

    public function testFormatDocumentWithClass(): void
    {
        $obj = new MockFormatDocument
        (
            name     : 'Hello' ,
            greeting : '{{name}}, World!'
        );

        $result = formatDocument($obj);

        $this->assertSame('Hello, World!', $result->greeting );
        $this->assertEquals( 'tests\oihana\core\documents\mocks\MockFormatDocument' , get_class($result), 'MockFormatDocument class should be preserved');
    }

    public function testFormatDocumentHandlesCircularReferences(): void
    {
        $a = new stdClass();
        $b = new stdClass();

        $a->ref = $b;
        $b->ref = $a;

        $a->name = 'Test';
        $b->msg  = 'Hello {{name}}';

        $formatted = formatDocument($a);

        $this->assertInstanceOf(stdClass::class, $formatted->ref);
        $this->assertSame('Hello Test', $formatted->ref->msg);
    }

    public function testFormatDocumentWithCustomFormatter(): void
    {
        $doc = [
            'version'  => '1.0',
            'message'  => 'App version: [[version]]',
        ];

        $formatter = function (string $value, $root, $prefix, $suffix) {
            return str_replace('[[version]]', $root['version'], $value);
        };

        $formatted = formatDocument($doc, '[[', ']]', '.', null, $formatter);

        $this->assertSame('App version: 1.0', $formatted['message']);
    }

    public function testPreserveMissingPlaceholdersInFormatDocument(): void
    {
        $data = [
            'name'    => 'Alice',
            'message' => 'Hello {{name}} {{lastname}}!'
        ];

        $result = formatDocument($data, preserveMissing: true);

        $this->assertSame('Hello Alice {{lastname}}!', $result['message']);
    }
}