<?php

namespace tests\oihana\core\strings ;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresOperatingSystem;
use PHPUnit\Framework\TestCase;

use function oihana\core\strings\escapeShellArgument;

#[CoversFunction('oihana\core\strings\escapeShellArgument')]
final class EscapeShellArgumentTest extends TestCase
{
    /**
     * @return array<string, array{string, string}>
     */
    public static function cases() :array
    {
        return
        [
            'plain text'                 => [ 'value'          , "'value'"            ] ,
            'an empty string'            => [ ''               , "''"                 ] ,
            'a space'                    => [ 'two words'      , "'two words'"        ] ,
            'a dollar is not expanded'   => [ 'a$HOME'         , "'a\$HOME'"          ] ,
            'backquotes run nothing'     => [ 'a`id`'          , "'a`id`'"            ] ,
            'a double quote'             => [ 'a"b'            , "'a\"b'"             ] ,
            'a backslash'                => [ 'a\\b'           , "'a\\b'"             ] ,
            'a single quote'             => [ "it's"           , "'it'\\''s'"         ] ,
            'only a single quote'        => [ "'"              , "''\\'''"            ] ,
            'non-ASCII stays as is'      => [ 'Café été'       , "'Café été'"         ] ,
            'a line feed'                => [ "two\nlines"     , "'two\nlines'"       ] ,
            'an exclamation mark'        => [ 'a!b'            , "'a!b'"              ] ,
        ] ;
    }

    #[DataProvider('cases')]
    public function testQuotes( string $raw , string $expected ) :void
    {
        $this->assertSame( $expected , escapeShellArgument( $raw ) ) ;
    }

    /**
     * The real proof: a shell reads the quoted value back, and the program receives it intact.
     */
    #[DataProvider('cases')]
    #[RequiresOperatingSystem('Linux|Darwin')]
    public function testAShellPassesTheValueIntact( string $raw , string $quoted ) :void
    {
        $argument = escapeShellArgument( $raw ) ;
        $output   = shell_exec( 'printf %s ' . $argument ) ;

        $this->assertSame( $quoted , $argument ) ;
        $this->assertSame( $raw , is_string( $output ) ? $output : '' ) ;
    }

    /**
     * Where escapeshellarg() loses the accents, after setlocale() in a C locale.
     */
    public function testTheResultDoesNotDependOnTheLocale() :void
    {
        $previous = setlocale( LC_CTYPE , 0 ) ;

        try
        {
            setlocale( LC_CTYPE , 'C' ) ;

            $this->assertSame( "'Café'" , escapeShellArgument( 'Café' ) ) ;
        }
        finally
        {
            setlocale( LC_CTYPE , $previous ) ;
        }
    }

    public function testANulByteIsRefused() :void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        $this->expectExceptionMessage( 'NUL byte' ) ;

        escapeShellArgument( "a\0b" ) ;
    }
}
