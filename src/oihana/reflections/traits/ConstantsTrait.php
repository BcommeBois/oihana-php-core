<?php

namespace oihana\reflections\traits;

use ReflectionClass;
use oihana\reflections\exceptions\ConstantException;

/**
 * The helper to creates constants enumeration classes.
 */
trait ConstantsTrait
{
    /**
     * The list of all constants.
     * @var array|null
     */
    private static ?array $ALL = null ;

    /**
     * The flipped list of all constants.
     * @var array|null
     */
    private static ?array $CONSTANTS = null ;

    /**
     * Returns an array of all constants in this enumeration.
     * @param int $flags The optional second parameter flags may be used to modify the comparison behavior using these values:
     * Comparison type flags:
     * <ul>
     * <li>SORT_REGULAR - compare items normally (don't change types)</li>
     * <li>SORT_NUMERIC - compare items numerically</li>
     * <li>SORT_STRING - compare items as strings</li>
     * <li>SORT_LOCALE_STRING - compare items as strings, based on the current locale.</li>
     * </ul>
     * @return array
     */
    public static function enums( int $flags = SORT_STRING ): array
    {
        $enums = array_unique( self::getAll() , $flags ) ;
        sort( $enums , $flags ) ;
        return $enums ;
    }

    /**
     * Returns a valid enumeration value or the default value.
     * @param mixed $value
     * @param mixed|null $default
     * @return mixed
     */
    public static function get( mixed $value , mixed $default = null ): mixed
    {
        return self::isValid( $value ) ? $value : $default ;
    }

    /**
     * Returns an array of constants in this class.
     * @return array<string, string>
     */
    public static function getAll(): array
    {
        if( is_null( static::$ALL ) )
        {
            static::$ALL = new ReflectionClass(__CLASS__ )->getConstants() ;
        }
        return static::$ALL ;
    }

    /**
     * Returns the constant name of the given value.
     * Useful for readability or debugging.
     * @param string $value The value to evaluates to find the constant name.
     * @return string|null The constant name.
     */
    public static function getConstant( string $value ): ?string
    {
        if( static::$CONSTANTS === null )
        {
            static::$CONSTANTS = array_flip( self::getAll() );
        }
        return static::$CONSTANTS[ $value ] ?? null;
    }

    /**
     * Checks if a given value code is valid (exists as a constant in this class).
     * Alias of the includes() method.
     * @param mixed $value
     * @param bool $strict [optional] <p>
     * If the third parameter strict is set to true
     * then the in_array function will also check the
     * types of the needle in the haystack.
     * </p>
     * @return bool True if the value exist, False otherwise.
     */
    public static function includes( mixed $value , bool $strict = false ): bool
    {
        return in_array( $value , self::getAll() , $strict ) ;
    }

    /**
     * Checks if a given value code is valid (exists as a constant in this class).
     * Alias of the isValid() method.
     * @param mixed $value The value to check.
     * @param bool $strict [optional] <p>
     *  If the third parameter strict is set to true
     *  then the in_array function will also check the
     *  types of the needle in the haystack.
     *  </p>
     * @return bool True if the value is valid, False otherwise.
     */
    public static function isValid( mixed $value , bool $strict = true ): bool
    {
        return in_array( $value , self::getAll() , $strict ) ;
    }

    /**
     * Validates if the passed-in value is a valid element in the current enum.
     * @param mixed $value
     * @param bool $strict [optional] <p>
     * If the third parameter strict is set to true then the in_array function will also check the
     * types of the needle in the haystack.
     * </p>
     * @return void
     * @throws ConstantException Thrown when the passed-in value is not a valid constant.
     */
    public static function validate( mixed $value , bool $strict = true ) : void
    {
        if( !self::isValid( $value , $strict ) )
        {
            throw new ConstantException( 'Invalid constant : ' . json_encode( $value ) ) ;
        }
    }
}