<?php

namespace oihana\core\arrays ;

/**
 * Lays one array over another, the way a configuration file is read: what the second says for a
 * key is what applies to that key.
 *
 * - **Tables** (associative arrays) are merged key by key, recursively: naming one setting keeps
 *   every other one the base provides.
 * - **Lists** are replaced whole: a list is a statement, not a contribution. `[ 'akismet' ]` over
 *   fifteen plugins gives one plugin, and `[]` gives none.
 * - Any other value, and a table laid over something that is not one, replaces what was there.
 * - Keys the second array does not mention are left as they are; keys only it has are added.
 *
 * This is the difference with its neighbours. {@see deepMerge()} appends to integer-keyed arrays,
 * so an overriding list could only ever add to the base, never shorten it.
 * `array_replace_recursive()` replaces lists **index by index**: `[ 'x' ]` over `[ 'a' , 'b' , 'c' ]`
 * gives `[ 'x' , 'b' , 'c' ]`, a list nobody wrote.
 *
 * An empty array counts as a list ({@see array_is_list()}), so it replaces: it is how an
 * override says "none".
 *
 * @param array<mixed> $base The defaults.
 * @param array<mixed> $over What is declared on top of them.
 *
 * @return array<mixed> The base with the declarations applied.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\overlay;
 *
 * $defaults = [ 'plugins' => [ 'a' , 'b' , 'c' ] , 'install' => [ 'path' => '/var/www' , 'quiet' => true ] ] ;
 * $site     = [ 'plugins' => [ 'x' ] , 'install' => [ 'quiet' => false ] ] ;
 *
 * overlay( $defaults , $site ) ;
 * // [ 'plugins' => [ 'x' ] , 'install' => [ 'path' => '/var/www' , 'quiet' => false ] ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.4.0
 */
function overlay( array $base , array $over ) :array
{
    foreach ( $over as $key => $value )
    {
        if ( is_array( $value ) && is_array( $base[ $key ] ?? null ) && !array_is_list( $value ) )
        {
            $base[ $key ] = overlay( $base[ $key ] , $value ) ;

            continue ;
        }

        $base[ $key ] = $value ;
    }

    return $base ;
}
