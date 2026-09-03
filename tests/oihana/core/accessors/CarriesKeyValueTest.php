<?php

declare(strict_types=1);

namespace tests\oihana\core\accessors;

use function oihana\core\accessors\carriesKeyValue;
use function oihana\core\accessors\hasKeyValue;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * A document whose properties are **typed**, to tell apart three states a plain
 * `stdClass` cannot express : declared and never filled, declared and holding
 * `null`, and holding an ordinary value.
 */
final class TypedArticle
{
    /** Declared with no default — uninitialized until something fills it. */
    public array $tags ;

    /** Declared AND initialized, holding null. */
    public ?array $labels = null ;

    /** An ordinary valued property. */
    public string $title = 'Untitled' ;

    /** Out of reach of an unrelated caller. */
    protected string $secret = 'hidden' ;
}

final class CarriesKeyValueTest extends TestCase
{
    // ------------------------------------------------------------------ objects

    public function testAnUninitializedTypedPropertyIsNotCarried(): void
    {
        $article = new TypedArticle() ;

        // The whole point of the function : the class declares `tags`, the object
        // holds nothing for it. hasKeyValue() cannot tell the two apart.
        $this->assertFalse( carriesKeyValue( $article , 'tags' ) ) ;
        $this->assertTrue ( hasKeyValue    ( $article , 'tags' ) ) ;
    }

    public function testAPropertyHoldingNullIsCarried(): void
    {
        // 🔑 Initialized, not non-null. `isset()` would answer false here, and
        // discarding such a property would discard every `= null` declaration.
        $this->assertTrue( carriesKeyValue( new TypedArticle() , 'labels' ) ) ;
    }

    public function testAValuedPropertyIsCarried(): void
    {
        $this->assertTrue( carriesKeyValue( new TypedArticle() , 'title' ) ) ;
    }

    public function testAPropertyFilledAfterwardsBecomesCarried(): void
    {
        $article = new TypedArticle() ;
        $this->assertFalse( carriesKeyValue( $article , 'tags' ) ) ;

        $article->tags = [] ;

        // An empty array IS a value : someone stated it.
        $this->assertTrue( carriesKeyValue( $article , 'tags' ) ) ;
    }

    public function testAPropertyOutOfScopeIsNotCarried(): void
    {
        $this->assertFalse( carriesKeyValue( new TypedArticle() , 'secret' ) ) ;
    }

    public function testAnUndeclaredPropertyIsNotCarried(): void
    {
        $this->assertFalse( carriesKeyValue( new TypedArticle() , 'author' ) ) ;
    }

    public function testADynamicPropertyIsCarried(): void
    {
        $doc = new stdClass() ;
        $doc->title = 'Book' ;
        $doc->empty = null ;

        $this->assertTrue ( carriesKeyValue( $doc , 'title' ) ) ;
        $this->assertTrue ( carriesKeyValue( $doc , 'empty' ) ) ;
        $this->assertFalse( carriesKeyValue( $doc , 'author' ) ) ;
    }

    // ------------------------------------------------------------------- arrays

    public function testOnAnArrayItAgreesWithHasKeyValue(): void
    {
        // `array_key_exists` already asks the right question, so nothing changes.
        $doc = [ 'name' => 'Alice' , 'empty' => null ] ;

        foreach ( [ 'name' , 'empty' , 'age' ] as $key )
        {
            $this->assertSame( hasKeyValue( $doc , $key ) , carriesKeyValue( $doc , $key ) ) ;
        }
    }

    // ------------------------------------------------------------- nested paths

    public function testANestedPathIsDelegated(): void
    {
        $doc = [ 'user' => [ 'name' => 'Alice' , 'empty' => null ] ] ;

        $this->assertTrue ( carriesKeyValue( $doc , 'user.name'  ) ) ;
        $this->assertTrue ( carriesKeyValue( $doc , 'user.empty' ) ) ;
        $this->assertFalse( carriesKeyValue( $doc , 'user.age'   ) ) ;
    }

    public function testANestedPathOnAnObjectIsDelegated(): void
    {
        $doc = (object) [ 'user' => (object) [ 'name' => 'Alice' ] ] ;

        $this->assertTrue ( carriesKeyValue( $doc , 'user.name' ) ) ;
        $this->assertFalse( carriesKeyValue( $doc , 'user.age'  ) ) ;
    }

    public function testACustomSeparatorIsHonoured(): void
    {
        $doc = [ 'user' => [ 'name' => 'Alice' ] ] ;

        $this->assertTrue( carriesKeyValue( $doc , 'user/name' , '/' ) ) ;
    }

    // -------------------------------------------------------------- validation

    public function testAnEmptyKeyIsRefused(): void
    {
        $this->expectException( InvalidArgumentException::class ) ;

        carriesKeyValue( new TypedArticle() , '' ) ;
    }
}
