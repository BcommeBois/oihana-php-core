<?php

namespace oihana\core ;

use oihana\core\arrays\CleanFlag ;

use function oihana\core\arrays\clean;

/**
 * Normalizes a value according to the given cleaning flags.
 *
 * - Arrays are cleaned recursively using the provided flags.
 * - Strings are trimmed if `CleanFlag::TRIM` is set, and empty strings become null if `CleanFlag::EMPTY` is set.
 * - Other scalar values are returned as-is, unless `CleanFlag::FALSY` is used.
 *
 * @param mixed $value The value to normalize (array or scalar).
 * @param int   $flags A bitmask of CleanFlag values. Defaults to `CleanFlag::DEFAULT | CleanFlag::RETURN_NULL`.
 *
 * @return mixed|null The normalized value, cleaned array, or null if the result is empty and RETURN_NULL is set.
 *
 * @example
 * ```php
 * normalize(['', ' foo ', null]);
 * // ['foo']
 *
 * normalize
 * ([
 *     'name'  => 'Alice',
 *     'email' => '  ',
 *     'age'   => null
 * ]);
 * // ['name' => 'Alice']
 *
 * normalize(['', null, '   ']);
 * // null
 *
 * normalize('   ');
 * // null
 *
 * normalize('bar');
 * // 'bar'
 *
 * normalize
 * ([
 *     'zero'    => 0,
 *     'empty'   => '',
 *     'nullVal' => null,
 *     'ok'      => 'valid'
 * ] , CleanFlag::FALSY | CleanFlag::RECURSIVE ) ;
 * // ['ok' => 'valid']
 *
 * normalize
 * ([
 *     'users' =>
 *     [
 *         ['name' => '', 'email' => 'bob@example.com'],
 *         ['name' => 'Alice', 'email' => '']
 *     ]
 * ] , CleanFlag::RECURSIVE | CleanFlag::EMPTY ) ;
 * // [
 * //     'users' => [
 * //         ['email' => 'bob@example.com'],
 * //         ['name' => 'Alice']
 * //     ]
 * // ]
 * ```
 *
 * @see clean() For array cleaning behavior.
 * @see CleanFlag Enumeration representing cleaning modes as bit flags.
 *
 * @package oihana\core
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.7
 */
function normalize( mixed $value, int $flags = CleanFlag::DEFAULT | CleanFlag::RETURN_NULL ): mixed
{
    if ( is_array( $value ) )
    {
        return clean( $value , $flags ) ;
    }

    if ( is_string( $value ) )
    {
        if ( CleanFlag::has( $flags , CleanFlag::TRIM ) )
        {
            $value = trim( $value ) ;
        }

        if ( CleanFlag::has( $flags , CleanFlag::EMPTY ) && $value === '' )
        {
            return null ;
        }
    }

    if ( CleanFlag::has( $flags, CleanFlag::FALSY ) && ! $value )
    {
        return null ;
    }

    return $value ;
}
