<?php

namespace tests\oihana\core\arrays;

use oihana\core\arrays\CleanFlag;
use function oihana\core\arrays\merge;

use oihana\core\options\MergeOption;
use oihana\core\options\NullsOption;
use PHPUnit\Framework\TestCase;

class MergeTest extends TestCase
{
    public function testSimpleMerge()
    {
        $a = ['foo' => 'bar', 'baz' => 1];
        $b = ['baz' => 2, 'new' => 3];

        $result = merge( $a , $b ) ;

        $this->assertSame
        ([
            'foo' => 'bar',
            'baz' => 2,
            'new' => 3,
        ], $result);
    }

    public function testDeepMerge()
    {
        $a = ['users' => [['name' => 'Alice']]];
        $b = ['users' => [['name' => 'Bob'  ]]];

        $result = merge( $a , $b , [ MergeOption::DEEP => true ] );

        $this->assertSame
        ([
            'users' =>
            [
                ['name' => 'Alice'],
                ['name' => 'Bob']
            ]
        ], $result);
    }

    public function testIndexedMergeWithUnique()
    {
        $a = ['tags' => ['php', 'unit']];
        $b = ['tags' => ['unit', 'testing']];

        $result = merge($a, $b, [
            MergeOption::INDEXED => true,
            MergeOption::UNIQUE => true
        ]);

        $this->assertSame([
            'tags' => ['php', 'unit', 'testing']
        ], $result);
    }

    public function testNullsOptionSkip()
    {
        $a = ['key' => 'value'];
        $b = ['key' => null, 'new' => null];

        $result = merge($a, $b, [
            MergeOption::NULLS => NullsOption::SKIP
        ]);

        $this->assertSame([
            'key' => 'value'
        ], $result);
    }

    public function testNullsOptionKeep()
    {
        $a = ['key' => 'value'];
        $b = ['key' => null, 'new' => null];

        $result = merge($a, $b, [
            MergeOption::NULLS => NullsOption::KEEP
        ]);

        $this->assertSame([
            'key' => 'value',
            'new' => null
        ], $result);
    }

    public function testNullsOptionOverwrite()
    {
        $a = ['key' => 'value'];
        $b = ['key' => null, 'new' => null];

        $result = merge( $a , $b ,
        [
            MergeOption::NULLS => NullsOption::OVERWRITE
        ]) ;

        $this->assertSame
        ([
            'key' => null,
            'new' => null
        ], $result);
    }

    public function testCleanOption()
    {
        $a = ['a' => null, 'b' => '  ', 'c' => 'ok'];
        $b = ['d' => '  '];

        $result = merge($a, $b, [
            MergeOption::CLEAN => CleanFlag::DEFAULT
        ]);

        $this->assertSame([
            'c' => 'ok'
        ], $result);
    }

    public function testCombinationDeepIndexedUnique()
    {
        $a = ['list' => [['x' => 1]]];
        $b = ['list' => [['x' => 1], ['x' => 2]]];

        $result = merge( $a , $b ,
        [
            MergeOption::DEEP    => true ,
            MergeOption::INDEXED => true ,
            MergeOption::UNIQUE  => true
        ]);

        $this->assertSame
        ([
            'list' =>
            [
                ['x' => 1],
                ['x' => 2]
            ]
        ] , $result ) ;
    }

    public function testDeepRecursiveAssociativeMerge()
    {
        $a = [ 'user' => [ 'name' => 'Marc' ] ] ;
        $b = [ 'user' => [ 'age'  => 30 ] ] ;

        $result = merge( $a , $b , [ MergeOption::DEEP => true ] ) ;

        $this->assertSame( [ 'user' => [ 'name' => 'Marc' , 'age' => 30 ] ] , $result ) ;
    }

    public function testIndexedStorageWrapsValues()
    {
        $result = merge( [] , [ 'a' => 1 ] , [ MergeOption::INDEXED => true ] ) ;
        $this->assertSame( [ 'a' => [ 1 ] ] , $result ) ;
    }

    public function testNumericKeysPreserveAndAppendWhenUnique()
    {
        // Duplicate value with UNIQUE is skipped (continue), distinct value is appended.
        $duplicate = merge( [ 0 => 'a' ] , [ 0 => 'a' ] ,
        [
            MergeOption::DEEP          => false ,
            MergeOption::PRESERVE_KEYS => true  ,
            MergeOption::UNIQUE        => true  ,
        ]);
        $this->assertSame( [ 'a' ] , $duplicate ) ;

        $distinct = merge( [ 0 => 'a' ] , [ 0 => 'b' ] ,
        [
            MergeOption::DEEP          => false ,
            MergeOption::PRESERVE_KEYS => true  ,
            MergeOption::UNIQUE        => true  ,
        ]);
        $this->assertSame( [ 'a' , 'b' ] , $distinct ) ;
    }
}
