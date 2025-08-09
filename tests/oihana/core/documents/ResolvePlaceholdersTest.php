<?php

namespace oihana\core\documents ;

use oihana\core\documents\mocks\MockFormatDocument;
use PHPUnit\Framework\TestCase;
use stdClass;

class ResolvePlaceholdersTest extends TestCase
{
    public function testSimpleReplacement(): void
    {
        $doc =
            [
            'name'    => 'Alice',
            'greeting' => 'Hello, {{name}}!'
        ];

        resolvePlaceholders($doc, $doc);

        $this->assertSame('Hello, Alice!', $doc['greeting']);
    }

    public function testNestedArrayReplacement(): void
    {
        $doc =
        [
            'user' =>
            [
                'firstname' => 'Bob',
                'profile'   =>
                [
                    'welcome' => 'Hi, {{user.firstname}}!'
                ]
            ]
        ];

        resolvePlaceholders($doc, $doc);

        $this->assertSame('Hi, Bob!', $doc['user']['profile']['welcome']);
    }

    public function testPreserveMissingPlaceholder(): void
    {
        $doc = [
            'name'    => 'Alice',
            'message' => 'Hello, {{name}} {{lastname}}!'
        ];

        resolvePlaceholders($doc, $doc, '{{', '}}', '.', null, null, true);

        $this->assertSame('Hello, Alice {{lastname}}!', $doc['message']);
    }

    public function testMissingIsReplacedWithEmptyStringByDefault(): void
    {
        $doc = [
            'message' => 'Hello, {{missing}}!'
        ];

        resolvePlaceholders($doc, $doc);

        $this->assertSame('Hello, !', $doc['message']);
    }

    public function testObjectPreservation(): void
    {
        $obj = new MockFormatDocument('Hi', '{{name}}, World!');

        resolvePlaceholders($obj, $obj);

        $this->assertSame('Hi, World!', $obj->greeting);
        $this->assertInstanceOf(MockFormatDocument::class, $obj);
    }

    public function testObjectWithStdClass(): void
    {
        $obj = new stdClass();
        $obj->name = 'X';
        $obj->message = 'Hello {{name}}';

        resolvePlaceholders($obj, $obj);

        $this->assertSame('Hello X', $obj->message);
    }

    public function testFormatterCallback(): void
    {
        $doc = [
            'version' => '1.0',
            'msg'     => 'v[[version]]'
        ];

        $formatter = function ( $val , $src , $prefix, $suffix)
        {
            return str_replace('[[version]]', $src['version'], $val);
        };

        resolvePlaceholders($doc, $doc, '[[', ']]', '.', null, $formatter);

        $this->assertSame('v1.0', $doc['msg']);
    }

    public function testCircularReferenceHandling(): void
    {
        $a = new stdClass();
        $b = new stdClass();

        $a->ref = $b;
        $b->ref = $a;

        $a->name = 'Test';
        $b->msg  = 'Hello {{name}}';

        resolvePlaceholders($a, $a);

        $this->assertSame('Hello Test', $a->ref->msg);
    }

    public function testPrefixDetectionOnlyFormatsStringsContainingPrefix(): void
    {
        $doc = [
            'raw'   => 'No placeholder here',
            'input' => '{{unknown}}'
        ];

        resolvePlaceholders($doc, [], preserveMissing:  true);

        $this->assertSame('No placeholder here', $doc['raw']);
        $this->assertSame('{{unknown}}', $doc['input']);
    }


    public function testFormatDocumentInPlaceWithBooleanFalse(): void
    {
        $target =
        [
            'simpleString'  => 'Hello World',
            'patternString' => '{{flag}}',
            'mixedString'   => 'Value is {{flag}}',
            'boolTrue'      => true,
            'boolFalse'     => false,
            'nested'        => (object)
            [
                'innerPattern' => '{{flag}}',
                'innerBool' => false,
            ],
        ];

        $source =
        [
            'flag' => false,
        ];

        resolvePlaceholders($target, $source);

        $this->assertTrue($target['boolTrue'] === true);
        $this->assertTrue($target['boolFalse'] === false);

        $this->assertSame('Hello World', $target['simpleString']);

        $this->assertSame(false, $target['patternString']);

        $this->assertSame('Value is ', $target['mixedString']);

        $this->assertSame(false, $target['nested']->innerPattern);
        $this->assertSame(false, $target['nested']->innerBool);
    }
}