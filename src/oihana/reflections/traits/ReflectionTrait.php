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

    /**
     * Returns the class short name.
     * @param object|string $class The object or the classname reference.
     * @return string
     * @throws ReflectionException
     */
    protected function getShortName( object|string $class ) : string
    {
        if( !isset( $this->__reflection ) )
        {
            $this->__reflection = new Reflection() ;
        }
        return $this->__reflection->shortName( $class ) ;
    }

    /**
     * Populates an object of the given class with data from the provided array.
     *
     * @param array $thing The data array used to hydrate the object.
     * @param string $class The classname of the object to be hydrated.
     * @return object The hydrated object of the given class.
     * @throws ReflectionException
     */
    protected function hydrate( array $thing , string $class ): object
    {
        if( !isset( $this->__reflection ) )
        {
            $this->__reflection = new Reflection() ;
        }
        return $this->__reflection->hydrate( $thing , $class ) ;
    }
}