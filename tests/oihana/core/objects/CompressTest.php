<?php

namespace oihana\core\objects ;

use PHPUnit\Framework\TestCase;
use stdClass;

class CompressTest extends TestCase
{
    public function testCompressRemovesNullProperties():void
    {
        $obj        = new stdClass();
        $obj->id    = 123;
        $obj->name  = "example";
        $obj->email = null;

        $result = compress( $obj );
        $result = get_object_vars( $result ) ;

        $this->assertArrayHasKey('id', $result );
        $this->assertArrayHasKey('name', $result );
        $this->assertArrayNotHasKey('email', $result );
    }

    public function testCompressKeepsExcludedNullProperties():void
    {
        $obj = new stdClass();
        $obj->id = 1;
        $obj->desc = null;

        $result = compress( $obj , [ 'excludes' => ['desc'] ] );
        $result = get_object_vars($result) ;

        $this->assertArrayHasKey('id', $result );
        $this->assertArrayHasKey('desc', $result ); // exclusion a fonctionné
    }

    public function testCompressKeepsAllIfNoNulls():void
    {
        $obj = new stdClass();
        $obj->name = "PHP";
        $obj->version = 8;

        $result = compress($obj);

        $this->assertEquals( (array) $obj , (array) $result );
    }

    public function testCompressEmptyObject():void
    {
        $obj = new stdClass();

        $result = compress($obj);

        $this->assertEquals(new stdClass(), $result);
    }

    public function testCompressRemovesMultipleNullsExceptExcluded():void
    {
        $obj = new stdClass();
        $obj->a = null;
        $obj->b = null;
        $obj->c = "valid";
        $obj->d = null;

        $result = compress( $obj , [ 'excludes' => ['b'] ] );
        $result = get_object_vars($result) ;

        $this->assertArrayNotHasKey('a', $result);
        $this->assertArrayHasKey('b', $result);
        $this->assertArrayHasKey('c', $result);
        $this->assertArrayNotHasKey('d', $result);
    }

    public function testCompressWithCustomConditions():void
    {
        $obj = new stdClass();
        $obj->a = null;
        $obj->b = '';
        $obj->c = 'keep';

        $options =
        [
            'conditions' =>
            [
                fn($v) => is_null($v),
                fn($v) => $v === ''
            ]
        ];

        $result = compress($obj, $options);
        $result = get_object_vars($result);

        $this->assertArrayNotHasKey('a', $result);
        $this->assertArrayNotHasKey('b', $result);
        $this->assertArrayHasKey('c', $result);
    }

    public function testCompressIgnoresInvalidConditionSilently():void
    {
        $obj = new stdClass();
        $obj->x = null;

        $options = [
            'conditions' => ['not_callable'], // string is not callable
            'throwable' => false
        ];

        // Devrait seulement retirer les nulls par défaut
        $result = compress($obj, $options);
        $result = get_object_vars($result) ;

        $this->assertArrayHasKey('x', $result);
    }

    public function testCompressRecursively()
    {
        $inner = new stdClass();
        $inner->val = null;
        $inner->keep = 1;

        $outer = new stdClass();
        $outer->nested = $inner;
        $outer->desc = null;

        $options = [
            'recursive' => true,
            'conditions' => fn($v) => is_null($v)
        ];

        $result = compress( $outer , $options );

        $result = get_object_vars( $result ) ;

        $this->assertArrayNotHasKey('desc', $result);
        $this->assertArrayHasKey('nested', $result);

        $result = get_object_vars( $outer->nested ) ;

        $this->assertArrayNotHasKey('val', $result);
        $this->assertArrayHasKey('keep', $result);
    }

    public function testCompressWithDepthLimit()
    {
        $level3 = new stdClass();
        $level3->a = null;
        $level3->b = 'keep';

        $level2 = new stdClass();
        $level2->child = $level3;

        $level1 = new stdClass();
        $level1->child = $level2;

        $options = [
            'recursive' => true,
            'depth' => 1, // on ne descend que jusqu’à level2
            'conditions' => fn($v) => is_null($v)
        ];

        $level1 = compress( $level1 , $options );

        $result = get_object_vars( $level1 ) ;
        $this->assertArrayHasKey('child', $result );

        $result = get_object_vars( $level1->child ) ;
        $this->assertArrayHasKey('child', $result );

        $result = get_object_vars( $level1->child->child ) ;
        $this->assertArrayHasKey('a', $result );
    }

    public function testCompressArrayOfObjects()
    {
        $obj = new stdClass();
        $obj->items =
        [
            (object) [ 'id' => 1 , 'remove' => null ] ,
            (object) [ 'id' => 2 , 'remove' => ''   ]
        ];

        $options =
        [
            'recursive' => true,
            'conditions' => [
                fn($v) => is_null($v),
                fn($v) => $v === ''
            ]
        ];

        $result = compress( $obj , $options ) ;

        $result = get_object_vars( $result ) ;

        $this->assertArrayHasKey('items', $result );
        $this->assertCount(2, $obj->items );

        $result = get_object_vars( $obj->items[0] ) ;
        $this->assertArrayNotHasKey('remove', $result );

        $result = get_object_vars( $obj->items[1] ) ;
        $this->assertArrayNotHasKey('remove', $result );
    }
}