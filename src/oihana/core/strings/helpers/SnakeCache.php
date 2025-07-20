<?php

namespace oihana\core\strings\helpers ;

/**
 * The cache of snake-cased words.
 * @package oihana\core\strings\helpers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
class SnakeCache
{
    /**
     * @var array
     */
    private static array $cache = [] ;

    /**
     * Returns the specific snake value from the cache.
     * @param string $key
     * @param string $delimiter
     * @return string|null
     */
    public static function get( string $key , string $delimiter ):?string
    {
        return self::$cache[ $key ][ $delimiter ] ?? null ;
    }

    /**
     * Indicates if the snake value is registered.
     * @param string $key
     * @param string $delimiter
     * @return bool
     */
    public static function has( string $key , string $delimiter ):bool
    {
        return isset( self::$cache[ $key ][ $delimiter ] ) ;
    }

    /**
     * Sets the snake value cache.
     * @param string $key
     * @param string $delimiter
     * @param string $value
     * @return void
     */
    public static function set( string $key , string $delimiter , string $value ):void
    {
        self::$cache[ $key ][ $delimiter ] = $value ;
    }

    /**
     * Flush the snake cache.
     * @return void
     */
    public static function flush():void
    {
        self::$cache = [] ;
    }
}