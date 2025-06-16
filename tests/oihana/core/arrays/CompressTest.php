<?php

namespace oihana\core\arrays ;

use stdClass;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

class CompressTest extends TestCase
{
    public function testBasicCompression()
    {
        $array =
        [
            'id' => 1,
            'created' => null,
            'name' => 'hello world',
            'description' => null
        ];

        $expected =
        [
            'id' => 1,
            'name' => 'hello world'
        ];

        $result = compress($array);
        $this->assertEquals($expected, $result);
    }

    public function testCompressionWithClone()
    {
        $array = [
            'id' => 1,
            'created' => null,
            'name' => 'hello world',
            'description' => null
        ];

        $options = ['clone' => true];
        $result = compress($array, $options);

        $this->assertNotSame($array, $result);
    }

    public function testCompressionWithExcludes()
    {
        $array = [
            'id' => 1,
            'created' => null,
            'name' => 'hello world',
            'description' => null
        ];

        $options = ['excludes' => ['description']];
        $expected = [
            'id' => 1,
            'name' => 'hello world',
            'description' => null // description est exclu, donc reste
        ];

        $result = compress($array, $options);
        $this->assertEquals($expected, $result);
    }

    public function testCompressionWithCustomConditions()
    {
        $array = [
            'id' => 1,
            'created' => null,
            'name' => 'hello world',
            'description' => '',
            'empty' => ''
        ];

        $options = ['conditions' => function($value) {
            return $value === null || $value === '';
        }];

        $expected = [
            'id' => 1,
            'name' => 'hello world'
        ];

        $result = compress($array, $options);
        $this->assertEquals($expected, $result);
    }

    public function testRecursiveCompression()
    {
        $array = [
            'id' => 1,
            'created' => null,
            'name' => 'hello world',
            'description' => null,
            'nested' => [
                'id' => 2,
                'created' => null,
                'name' => 'nested world',
                'description' => null
            ]
        ];

        $options = ['recursive' => true];
        $expected = [
            'id' => 1,
            'name' => 'hello world',
            'nested' => [
                'id' => 2,
                'name' => 'nested world'
            ]
        ];

        $result = compress($array, $options);
        $this->assertEquals($expected, $result);
    }

    public function testCompressionWithObjects()
    {
        $object = new stdClass();
        $object->id = 1;
        $object->created = null;
        $object->name = 'hello world';
        $object->description = null;

        $array = [
            'id' => 1,
            'created' => null,
            'name' => 'hello world',
            'description' => null,
            'object' => $object
        ];

        $options = ['recursive' => true];
        $expected =
        [
            'id'     => 1 ,
            'name'   => 'hello world' ,
            'object' => (object)
            [
                'id' => 1,
                'name' => 'hello world'
            ]
        ];

        $result = compress($array, $options);

        // Convertir l'objet attendu en tableau pour la comparaison
        $expectedObject = (array) $expected['object'] ?? []  ;
        $resultObject   = (array) $result['object']   ?? []  ;
        $this->assertEquals( $expectedObject , $resultObject );
    }

    public function testCompressionWithThrowable()
    {
        $this->expectException( InvalidArgumentException::class ) ;

        $array =
        [
            'id'          => 1,
            'created'     => null,
            'name'        => 'hello world',
            'description' => null
        ];

        $options = [ 'conditions' => 'not a callable' ];
        compress( $array , $options );
    }

    public function testCompressionWithMaxDepth()
    {
        $array =
        [
            'id'          => 1,
            'created'     => null,
            'name'        => 'hello world',
            'description' => null,
            'nested' =>
            [
                'id' => 2,
                'created' => null,
                'name' => 'nested world',
                'description' => null,
                'deeplyNested' => [
                    'id' => 3,
                    'created' => null,
                    'name' => 'deeply nested world',
                    'description' => null
                ]
            ]
        ];

        $options = [ 'recursive' => true, 'depth' => 1 ] ;
        $expected =
        [
            'id' => 1,
            'name' => 'hello world',
            'nested' => [
                'id' => 2,
                'name' => 'nested world',
                'deeplyNested' => [
                    'id' => 3,
                    'created' => null,
                    'name' => 'deeply nested world',
                    'description' => null
                ]
            ]
        ];

        $result = compress($array, $options);

        $this->assertEquals($expected, $result);
    }

    public function testCompressionWithIndexedArray()
    {
        $array = [
            0 => 1,
            1 => null,
            2 => 'hello world',
            3 => null
        ];

        $expected = [
            0 => 1 ,
            1 => 'hello world'
        ];

        $result = compress($array);

        $this->assertEquals($expected, $result);
    }
}
