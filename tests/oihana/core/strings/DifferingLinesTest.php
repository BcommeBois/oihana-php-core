<?php

namespace tests\oihana\core\strings ;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function oihana\core\strings\differingLines;

#[CoversFunction('oihana\core\strings\differingLines')]
final class DifferingLinesTest extends TestCase
{
    /**
     * @return array<string, array{string, string, int}>
     */
    public static function textProvider() :array
    {
        return
        [
            'identical'                  => [ "a\nb\n"    , "a\nb\n"       , 0 ] ,
            'both empty'                 => [ ''          , ''             , 0 ] ,
            'one line changed'           => [ "a\nb\n"    , "a\nc\n"       , 2 ] ,
            'one line added'             => [ "a\n"       , "a\nb\n"       , 1 ] ,
            'one line removed'           => [ "a\nb\n"    , "a\n"          , 1 ] ,
            'a line repeated once more'  => [ "a\nb\n"    , "a\nb\nb\n"    , 1 ] ,
            'three lines added'          => [ "a\n"       , "a\nb\nc\nd\n" , 3 ] ,
            'two lines swapped'          => [ "a\nb\n"    , "b\na\n"       , 1 ] ,
            'trailing whitespace'        => [ "a\n"       , "a \n"         , 2 ] ,
            'a final newline only'       => [ "a"         , "a\n"          , 1 ] ,
            'a line that looks numeric'  => [ "1\n2\n"    , "1\n3\n"       , 2 ] ,
        ] ;
    }

    #[DataProvider('textProvider')]
    public function testTheCount( string $a , string $b , int $expected ) :void
    {
        $this->assertSame( $expected , differingLines( $a , $b ) ) ;
    }

    public function testTheCountIsTheSameBothWays() :void
    {
        $a = "server {\n  listen 80;\n  root /a;\n}\n" ;
        $b = "server {\n  listen 443;\n  root /a;\n  index x;\n}\n" ;

        $this->assertSame( 3 , differingLines( $a , $b ) ) ;
        $this->assertSame( differingLines( $a , $b ) , differingLines( $b , $a ) ) ;
    }
}
