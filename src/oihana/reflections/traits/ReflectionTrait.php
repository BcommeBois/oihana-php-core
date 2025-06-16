<?php

namespace oihana\reflections\traits ;

use oihana\reflections\Reflection;
use ReflectionException;

trait ReflectionTrait
{
    /**
     * @var string|null
     */
    private ?string $__className = null ;

    /**
     * @var ?Reflection
     */
    private ?Reflection $__reflection = null ;

    /**
     * Returns the short class name of the given object/class.
     * @param object|string $class The object or the classname reference.
     * @return string
     */
    protected function getClassName( object|string $class ) : string
    {
        if( !isset( $this->__reflection ) )
        {
            $this->__reflection = new Reflection() ;
        }

        if( !isset( $this->__className ) )
        {
            $this->__className = $this->__reflection->className( $class ) ;
        }

        return $this->__className ;
    }

    /**
     * Returns the list of all constants of the given object/class.
     * @param object|string $class
     * @return array
     * @throws ReflectionException
     */
    protected function getConstants( object|string $class ) : array
    {
        if( !isset( $this->__reflection ) )
        {
            $this->__reflection = new Reflection() ;
        }
        return $this->__reflection->constants( $class ) ;
    }

    /**
     * Internal methods to get the public
     * @param object|string $class The object or the classname reference.
     * @return array
     * @throws ReflectionException
     */
    protected function getPublicProperties( object|string $class ) : array
    {
        if( !isset( $this->__reflection ) )
        {
            $this->__reflection = new Reflection() ;
        }
        return $this->__reflection->properties( $class ) ;
    }
}