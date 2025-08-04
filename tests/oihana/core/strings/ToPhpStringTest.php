<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;
use stdClass;

class ToPhpStringTest extends TestCase
{
    public function testSimpleValues()
    {
        $this->assertEquals('42'      , toPhpString(42));
        $this->assertEquals("'hello'" , toPhpString('hello' ) );
        $this->assertEquals('true'    , toPhpString(true));
        $this->assertEquals('null'    , toPhpString(null));
        $this->assertEquals('3.14'    , toPhpString(3.14));
    }

    public function testArrayWithBrackets()
    {
        $array    = [ 'foo' => 'bar', 'baz' => 123 ] ;
        $expected = "['foo' => 'bar', 'baz' => 123]" ;
        $this->assertSame( $expected, toPhpString( $array , ['useBrackets' => true , 'inline' => true ]));
    }

    public function testArrayWithArrayAndObject()
    {
        $data = [ 'list' => [1, 2, 3], 'obj'  => (object)['a' => 1 , 'b' => 2 ] ];

        $result = toPhpString($data, ['useBrackets' => false]);

        $this->assertStringContainsString("array(", $result);
        $this->assertStringContainsString("(object)array(", $result);
        $this->assertStringContainsString("'obj' => (object)array(", $result);  // ← CORRECTION ICI
        $this->assertStringContainsString("'list' => array(", $result);

        $this->assertStringContainsString("'a' => 1", $result);
        $this->assertStringContainsString("'b' => 2", $result);
    }

    public function testNestedArray()
    {
        $nested = [
            'outer' => [
                'inner' => ['a', 'b']
            ]
        ];

        $expected = "array('outer' => array('inner' => array('a', 'b')))";
        $this->assertEquals($expected, toPhpString($nested, ['inline' => true]));
    }

    public function testObject()
    {
        $obj = (object)['foo' => 'bar'];
        $result = toPhpString($obj);
        $this->assertStringStartsWith('(object)', $result);
        $this->assertStringContainsString("'foo' => 'bar'", $result);
    }

    public function testSequentialArray()
    {
        $array = [10, 20, 30];
        $expected = '[10, 20, 30]';
        $this->assertEquals($expected, toPhpString($array, ['useBrackets' => true, 'inline' => true]));
    }

    public function testMultilineIndentedOutput()
    {
        $data = [
            'user' => [
                'name' => 'Alice',
                'languages' => ['fr', 'en']
            ]
        ];

        $result = toPhpString($data, [
            'useBrackets' => true,
            'inline' => false,
            'indent' => '  '
        ]);

        $this->assertStringContainsString("[\n", $result);
        $this->assertStringContainsString("'languages' => [\n", $result);
        $this->assertStringContainsString("'name' => 'Alice'", $result);
    }

    public function testMaxDepth()
    {
        $data = ['a' => ['b' => ['c' => ['d' => ['e' => 'end']]]]];

        $result = toPhpString($data, [
            'maxDepth' => 3
        ]);

        $this->assertStringContainsString("'<max-depth-reached>'", $result);
    }

    public function testStdClassConversion()
    {
        $obj = new stdClass();
        $obj->x = 1;
        $obj->y = 2;

        $result = toPhpString($obj, ['useBrackets' => true]);
        $this->assertStringStartsWith('(object)', $result);
        $this->assertStringContainsString("'x' => 1", $result);
        $this->assertStringContainsString("'y' => 2", $result);
    }

    public function testSpecialCharacters()
    {
        $array = ['he\'s' => "it's \"ok\""];
        $result = toPhpString($array, ['useBrackets' => true]);
        $this->assertStringContainsString("'he\\'s' => 'it\\'s \"ok\"'", $result);
    }

    public function testResourceHandling()
    {
        $handle = fopen('php://memory', 'r');
        $result = toPhpString($handle);
        fclose($handle);

        $this->assertSame("'<resource of type stream>'", $result);
    }

    public function testClosureHandling()
    {
        $closure = fn() => 'foo';
        $result = toPhpString($closure);

        $this->assertSame("'<closure>'", $result);
    }

    public function testRecursiveObject()
    {
        $obj = new stdClass();
        $obj->self = $obj;

        $result = toPhpString($obj, [
            'maxDepth' => 5,
            'useBrackets' => true
        ]);

        $this->assertStringContainsString("'<circular-ref>'", $result);
    }

    public function testAnonymousClass()
    {
        $anon = new class
        {
            public string $title = 'test';
            private int $secret = 42;
        };

        $result = toPhpString($anon, [
            'useBrackets' => true
        ]);

        // Seul $title (public) est récupéré par get_object_vars
        $this->assertStringStartsWith('(object)', $result);
        $this->assertStringContainsString("'title' => 'test'", $result);
        $this->assertStringNotContainsString('secret', $result); // private non exporté
    }

    public function testObjectWithCustomSerialize()
    {
        $obj = new class
        {
            public string $visible = 'yes';
            private string $hidden = 'no';

            public function __serialize(): array
            {
                return ['custom' => 'serialized'];
            }
        };

        $result = toPhpString($obj, ['useBrackets' => true]);

        $this->assertStringStartsWith('(object)', $result);
        $this->assertStringContainsString("'visible' => 'yes'", $result);
        $this->assertStringNotContainsString('custom', $result); // __serialize() ignored
        $this->assertStringNotContainsString('hidden', $result); // private ignored
    }

    public function testFormattedSnapshot()
    {
        $data = [
            'user' => [
                'name' => 'Alice',
                'roles' => ['admin', 'editor'],
                'profile' => (object)['active' => true, 'age' => 30]
            ]
        ];

        $expected = <<<PHP
[
    'user' => [
        'name' => 'Alice',
        'roles' => [
            'admin',
            'editor'
        ],
        'profile' => (object)[
            'active' => true,
            'age' => 30
        ]
    ]
]
PHP;

        $result = toPhpString($data, [
            'useBrackets' => true,
            'inline' => false,
            'indent' => '    ',
        ]);

        $this->assertSame($expected, $result);
    }

    public function testCircularReference(): void
    {
        $obj = new stdClass();
        $obj->self = $obj;

        $result = toPhpString($obj, ['useBrackets' => true]);

        $this->assertStringContainsString("'<circular-ref>'", $result);
        $this->assertStringNotContainsString("'<max-depth-reached>'", $result);
    }

    public function testMaxDepthReached(): void
    {
        $nested = [];
        $current = &$nested;

        // Génère un tableau imbriqué à plus de 5 niveaux
        for ($i = 0; $i < 10; $i++) {
            $current['level' . $i] = [];
            $current = &$current['level' . $i];
        }

        $result = toPhpString($nested, [
            'useBrackets' => true,
            'maxDepth'    => 5,
        ]);

        $this->assertStringContainsString("'<max-depth-reached>'", $result);
        $this->assertStringNotContainsString("'<circular-ref>'", $result);
    }

    public function testQuoteStyleDouble()
    {
        $str = "line1\nline2";
        $result = toPhpString($str, ['quote' => 'double']);
        $this->assertSame('"line1\\nline2"', $result);
    }

    public function testHumanReadableMode()
    {
        $data = ['pi' => 3.14159, 'bool' => true, 'null' => null];
        $result = toPhpString($data, ['useBrackets' => true, 'humanReadable' => true]);
        $this->assertStringContainsString("'pi' => 3.14159", $result);
        $this->assertStringContainsString("'bool' => true", $result);
        $this->assertStringContainsString("'null' => null", $result);
    }

    public function testExtremeScalars()
    {
        $this->assertEquals('INF', toPhpString(INF));
        $this->assertEquals('-INF', toPhpString(-INF));
        $this->assertEquals('NAN', toPhpString(NAN)); // var_export(NAN, true) == 'NAN'
    }

    public function testDateTimeHandling()
    {
        $date = new \DateTime('2024-05-12 08:00:00');
        $result = toPhpString($date);
        $this->assertStringContainsString('DateTime', $result);
        $this->assertStringContainsString('2024-05-12', $result);
    }

    public function testCompactStringsOption()
    {
        $data = ['description' => "Line 1\nLine 2"];
        $result = toPhpString($data, [
            'compact' => true,
            'useBrackets'    => true,
            'quote'          => 'double',
        ]);

        $this->assertStringContainsString('"Line 1\\nLine 2"', $result);
        $this->assertStringNotContainsString("\nLine 2", $result);
    }

    public function testEnumHandling()
    {
        if (!enum_exists('Suit')) {
            eval('enum Suit { case Hearts; case Spades; }');
        }

        $this->assertSame('oihana\\core\\strings\\Suit::Hearts', toPhpString(Suit::Hearts));
    }

}