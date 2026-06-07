<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\firstWhere;

use PHPUnit\Framework\TestCase;

class FirstWhereTest extends TestCase
{
    public function testReturnsFirstMatch() : void
    {
        $users =
        [
            [ 'name' => 'Alice' , 'active' => false ] ,
            [ 'name' => 'Bob'   , 'active' => true  ] ,
            [ 'name' => 'Carol' , 'active' => true  ] ,
        ];
        $this->assertSame( $users[ 1 ] , firstWhere( $users , fn( $u ) => $u[ 'active' ] ) ) ;
    }

    public function testReturnsDefaultWhenNoMatch() : void
    {
        $this->assertSame( -1 , firstWhere( [ 1 , 2 ] , fn( $n ) => $n > 10 , -1 ) ) ;
    }

    public function testPredicateReceivesValueAndKey() : void
    {
        $items = [ 'a' => 1 , 'b' => 2 ] ;
        $this->assertSame( 2 , firstWhere( $items , fn( $value , $key ) => $key === 'b' ) ) ;
    }
}
