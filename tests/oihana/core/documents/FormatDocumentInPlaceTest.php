<?php

namespace oihana\core\documents ;

use oihana\core\documents\mocks\MockFormatDocument;
use PHPUnit\Framework\TestCase;
use stdClass;

class FormatDocumentInPlaceTest extends TestCase
{
    public function testSimpleReplacement(): void
    {
        $doc = [
            'name'    => 'Alice',
            'greeting' => 'Hello, {{name}}!'
        ];

        formatDocumentInPlace($doc, $doc);

        $this->assertSame('Hello, Alice!', $doc['greeting']);
    }

    public function testNestedArrayReplacement(): void
    {
        $doc = [
            'user' => [
                'firstname' => 'Bob',
                'profile' => [
                    'welcome' => 'Hi, {{user.firstname}}!'
                ]
            ]
        ];

        formatDocumentInPlace($doc, $doc);

        $this->assertSame('Hi, Bob!', $doc['user']['profile']['welcome']);
    }

    public function testPreserveMissingPlaceholder(): void
    {
        $doc = [
            'name'    => 'Alice',
            'message' => 'Hello, {{name}} {{lastname}}!'
        ];

        formatDocumentInPlace($doc, $doc, '{{', '}}', '.', null, null, true);

        $this->assertSame('Hello, Alice {{lastname}}!', $doc['message']);
    }

    public function testMissingIsReplacedWithEmptyStringByDefault(): void
    {
        $doc = [
            'message' => 'Hello, {{missing}}!'
        ];

        formatDocumentInPlace($doc, $doc);

        $this->assertSame('Hello, !', $doc['message']);
    }

    public function testObjectPreservation(): void
    {
        $obj = new MockFormatDocument('Hi', '{{name}}, World!');

        formatDocumentInPlace($obj, $obj);

        $this->assertSame('Hi, World!', $obj->greeting);
        $this->assertInstanceOf(MockFormatDocument::class, $obj);
    }

    public function testObjectWithStdClass(): void
    {
        $obj = new stdClass();
        $obj->name = 'X';
        $obj->message = 'Hello {{name}}';

        formatDocumentInPlace($obj, $obj);

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

        formatDocumentInPlace($doc, $doc, '[[', ']]', '.', null, $formatter);

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

        formatDocumentInPlace($a, $a);

        $this->assertSame('Hello Test', $a->ref->msg);
    }

    public function testPrefixDetectionOnlyFormatsStringsContainingPrefix(): void
    {
        $doc = [
            'raw'   => 'No placeholder here',
            'input' => '{{unknown}}'
        ];

        formatDocumentInPlace($doc, [], preserveMissing:  true);

        $this->assertSame('No placeholder here', $doc['raw']);
        $this->assertSame('{{unknown}}', $doc['input']);
    }


    public function testFormatDocumentInPlaceWithBooleanFalse(): void
    {
        $target = [
            'simpleString' => 'Hello World',
            'patternString' => '{{flag}}',
            'mixedString' => 'Value is {{flag}}',
            'boolTrue' => true,
            'boolFalse' => false,
            'nested' => (object)[
                'innerPattern' => '{{flag}}',
                'innerBool' => false,
            ],
        ];

        $source = [
            'flag' => false,
        ];

        // Appelle ta fonction (assure-toi qu'elle est chargée/autoloadée)
        formatDocumentInPlace($target, $source);

        // Valeur non string reste inchangée
        $this->assertTrue($target['boolTrue'] === true);
        $this->assertTrue($target['boolFalse'] === false);

        // String sans pattern ne change pas
        $this->assertSame('Hello World', $target['simpleString']);

        // String avec pattern EXACT égale à la clé, doit être remplacée par bool false (pas string '')
        $this->assertSame(false, $target['patternString']);
        //
        // // String avec pattern + autre texte, doit devenir une string où false devient ''
        $this->assertSame('Value is ', $target['mixedString']);
        //
        // // Objet imbriqué, même logique
        $this->assertSame(false, $target['nested']->innerPattern);
        $this->assertSame(false, $target['nested']->innerBool);
    }
}