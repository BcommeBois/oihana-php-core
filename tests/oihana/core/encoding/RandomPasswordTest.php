<?php

namespace tests\oihana\core\encoding ;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

use function oihana\core\encoding\randomPassword;

#[CoversFunction('oihana\core\encoding\randomPassword')]
final class RandomPasswordTest extends TestCase
{
    /**
     * @throws RandomException
     */
    public function testTheDefaultIsTwentyEightLettersAndDigits() :void
    {
        $password = randomPassword() ;

        $this->assertSame( 28 , strlen( $password ) ) ;
        $this->assertMatchesRegularExpression
        (
            '/^[a-zA-Z0-9]+$/' ,
            $password ,
            'Punctuation has to be escaped by a shell, a configuration writer and a command-line tool in turn, and one of them always forgets.'
        ) ;
    }

    /**
     * Sampled rather than tried once: a single draw of 28 from the full alphabet misses all five
     * ambiguous characters about once in ten, so a broken alphabet would pass nine times in ten.
     *
     * @throws RandomException
     */
    public function testTheFiveAmbiguousCharactersNeverAppear() :void
    {
        $many = '' ;

        for ( $i = 0 ; $i < 200 ; $i++ )
        {
            $many .= randomPassword() ;
        }

        $this->assertDoesNotMatchRegularExpression( '/[lI1O0]/' , $many , 'These five are misread off a screen, and this password is copied by hand.' ) ;
    }

    /**
     * And every one of the 57 others does, given enough draws: an alphabet cut short would weaken
     * every password without any of them looking wrong.
     *
     * @throws RandomException
     */
    public function testTheWholeAlphabetIsUsed() :void
    {
        $many = '' ;

        for ( $i = 0 ; $i < 200 ; $i++ )
        {
            $many .= randomPassword() ;
        }

        // 5 600 draws over 57 characters: the chance that one of them never comes up is about
        // 1 in 10^42.

        $expected = str_split( 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789' ) ;

        sort( $expected ) ;

        $this->assertSame( implode( '' , $expected ) , count_chars( $many , 3 ) ) ;
    }

    /**
     * @throws RandomException
     */
    public function testTwoPasswordsAreNotTheSame() :void
    {
        $passwords = [] ;

        for ( $i = 0 ; $i < 50 ; $i++ )
        {
            $passwords[] = randomPassword() ;
        }

        $this->assertCount( 50 , array_unique( $passwords ) ) ;
    }

    /**
     * @return array<string, array{int, int}>
     */
    public static function lengthProvider() :array
    {
        return
        [
            'longer'            => [ 40  , 40 ] ,
            'exactly the floor' => [ 16  , 16 ] ,
            'under the floor'   => [ 4   , 16 ] ,
            'zero'              => [ 0   , 16 ] ,
            'negative'          => [ -10 , 16 ] ,
        ] ;
    }

    /**
     * A caller asking for too short a password gets a usable one, not an error.
     *
     * @throws RandomException
     */
    #[DataProvider('lengthProvider')]
    public function testTheLength( int $asked , int $expected ) :void
    {
        $this->assertSame( $expected , strlen( randomPassword( $asked ) ) ) ;
    }
}
