<?php

namespace oihana\traits;

use ReflectionClass;
use oihana\enums\Char;

/**
 * Trait ToStringTrait
 *
 * Provides a basic string representation for an object by returning its class name
 * wrapped in square brackets or other defined characters.
 *
 * This trait uses reflection to dynamically obtain the short class name of the object.
 * It also caches the result for performance on repeated calls to `__toString()`.
 *
 * Methods:
 * - __toString(): Returns a string like "[ClassName]".
 *
 * Example usage:
 * ```php
 * use oihana\traits\ToStringTrait;
 *
 * class MyObject {
 *     use ToStringTrait;
 * }
 *
 * echo (new MyObject()); // Output: [MyObject]
 * ```
 */
trait ToStringTrait
{
    /**
     * Returns a String representation of the object.
     * @return string A string representation of the object.
     */
    public function __toString():string
    {
        if( !isset( $__className ) )
        {
            $this->__className = $this->__getType() ;
        }
        return Char::LEFT_BRACKET . $this->__className . Char::RIGHT_BRACKET ;
    }

    /**
     * @var string|null
     */
    private ?string $__className ;

    /**
     * Retrieves the short name of the class for the current instance.
     * @return string The short class name of the current object.
     */
    private function __getType(): string
    {
        return new ReflectionClass( $this )->getShortName() ;
    }
}