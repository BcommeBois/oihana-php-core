<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use InvalidArgumentException;

/**
 * Checks whether a document actually **carries a value** for a key — a stricter {@see hasKeyValue()}.
 *
 * The two answer different questions on an object, and the difference matters as soon as typed properties are involved :
 *
 * - {@see hasKeyValue()} falls back on `property_exists()`, which is true of every
 *   property the **class declares**, initialized or not ;
 * - this one asks whether the **instance holds a value**, which is what a caller
 *   iterating over supplied data usually means.
 *
 * A typed property declared without a default is *uninitialized* until something fills
 * it : the class declares it, the object carries nothing. Treating the two alike makes
 * a consumer act on a field nobody ever supplied — and write back whatever it computes
 * from nothing, turning an absence into an assertion.
 *
 * On an **array** the behaviour is identical to {@see hasKeyValue()} : `array_key_exists`
 * already asks the right question there.
 *
 * 🔑 **Initialized, never non-null.** A property holding `null` **is** carried, and this
 * function says so — like `hasKeyValue()` and unlike `isset()`. A great many properties
 * are declared `= null` on purpose, and reading `null` as absence would discard them all.
 *
 * ⚠️ Only the properties **visible from the calling scope** are considered, which for an
 * unrelated caller means the public ones. A private or protected property of another
 * class reads as not carried — it was unreachable to that caller anyway.
 *
 * Nested paths are delegated to {@see hasKeyValue()}, which owns the traversal.
 *
 * @param array<array-key, mixed>|object $document  The document (array or object) to inspect.
 * @param string                         $key       The key or property path to check. Supports nesting with separator.
 * @param non-empty-string               $separator Separator used for nested paths. Default is '.'.
 * @param bool|null                      $isArray   Optional: true if document is an array, false if object, null to auto-detect.
 *
 * @return bool True when the document holds a value for that key.
 *
 * @throws InvalidArgumentException If the document or key is invalid.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.2.0
 *
 * @example
 * ```php
 * class Article
 * {
 *     public array   $tags ;          // declared, never initialized
 *     public ?array  $labels = null ; // declared AND initialized, holding null
 * }
 *
 * $article = new Article() ;
 *
 * hasKeyValue    ( $article , 'tags' ) ; // true  — the class declares it
 * carriesKeyValue( $article , 'tags' ) ; // false — the object carries nothing
 *
 * hasKeyValue    ( $article , 'labels' ) ; // true
 * carriesKeyValue( $article , 'labels' ) ; // true — null IS a value
 * ```
 *
 * ```php
 * $doc = [ 'name' => 'Alice' , 'empty' => null ] ;
 *
 * carriesKeyValue( $doc , 'name'  ) ; // true
 * carriesKeyValue( $doc , 'empty' ) ; // true
 * carriesKeyValue( $doc , 'age'   ) ; // false
 * ```
 *
 * ```php
 * $doc = (object) [ 'user' => (object) [ 'name' => 'Alice' ] ] ;
 *
 * carriesKeyValue( $doc , 'user.name' ) ; // true
 * carriesKeyValue( $doc , 'user.age'  ) ; // false
 * ```
 */
function carriesKeyValue
(
    array|object $document ,
          string $key ,
          string $separator = '.' ,
           ?bool $isArray   = null
)
:bool
{
    if ( is_object( $document ) && !str_contains( $key , $separator ) )
    {
        assertDocumentKeyValid( $document , $key , $separator , $isArray ) ;

        return array_key_exists( $key , get_object_vars( $document ) ) ;
    }

    return hasKeyValue( $document , $key , $separator , $isArray ) ;
}
