<?php

namespace oihana\reflections;

use oihana\enums\Char;
use oihana\interfaces\Equatable;

/**
 * Class Version
 *
 * Represents a software version using four components: major, minor, build, and revision.
 * These components are internally encoded into a single 32-bit integer for compact storage and efficient comparison.
 *
 * ### Usage Example:
 * * ```php
 * use oihana\reflections\Version ;
 *
 * $version1 = new Version( 2 , 1 , 1 , 110 ) ;
 * $version2 = new Version( 3 , 1 , 1 , 110 ) ;
 * $version3 = new Version( 1 , 2 , 3 , 4 ) ;
 *
 * $version1->major = 3 ;
 *
 * echo('version   : ' . $version1 ) ;
 * echo( 'major    : ' . $version1->getMajor() ) ;
 * echo( 'minor    : ' . $version1->getMinor() ) ;
 * echo( 'build    : ' . $version1->getBuild() ) ;
 * echo( 'revision : ' . $version1->getRevision() ) ;
 *
 * echo( 'version 1 : ' . $version1->valueOf() ) ;
 * echo( 'version 2 : ' . $version2->valueOf() ) ;
 * echo( 'version 3 : ' . $version3->valueOf() ) ;
 *
 * echo( "equals( 'toto' )    : " . ($version1->equals('toto')    ? 'true' : 'false' )) ;
 * echo( "equals( $version2 ) : " . ($version1->equals($version2) ? 'true' : 'false' )) ;
 * echo( "equals( $version3 ) : " . ($version1->equals($version3) ? 'true' : 'false' )) ;
 * ```
 * ### Key Features:
 * - Supports dynamic property access (`$version->major`, `$version->minor`, etc.) through magic methods.
 * - Efficiently encodes and decodes version components using bitwise operations.
 * - Customizable string representation via the `separator` and `fields` properties.
 * - Implements equality checking through the `equals()` method.
 * - Provides a `fromString()` static method for instantiating versions from formatted strings.
 */
class Version implements Equatable
{
    /**
     * Creates a new Version instance.
     * @param int $major
     * @param int $minor
     * @param int $build
     * @param int $revision
     */
    public function __construct( int $major = 0, int $minor = 0, int $build = 0, int $revision = 0 )
    {
        $this->_value = ( $major << 28 ) | ( $minor << 24 ) | ( $build << 16 ) | $revision ;
    }

    /**
     * The fields limit.
     */
    public int $fields = 0 ;

    /**
     * The separator expression.
     */
    public string $separator = Char::DOT ;

    /**
     * We don't really need an equals method as we override the valueOf, we can do something as
     * <pre>
     * $v1 = new Version( 1,0,0,0 );
     * $v2 = new Version( 1,0,0,0 );
     * echo( json_encode( v1->equals( v2 ) ) ) ; //true
     * </pre>
     * A cast to Number/int force the valueOf, not ideal but sufficient, and the same for any other operators.
     * But as we keep Equatable for now, then we have no reason to not use it.
     */
    public function equals( mixed $value ) :bool
    {
        if( $value instanceof Version )
        {
            return $this->_value == $value->valueOf() ;
        }

        return false ;
    }

    /**
     * Indicates the build value of this version.
     */
    public function getBuild():int
    {
        return $this->RRR( ( $this->_value & 0x00FF0000 ) , 16 );
    }

    /**
     * Indicates the major value of this version.
     */
    public function getMajor():int
    {
        return $this->RRR( $this->_value , 28 ) ;
    }

    /**
     * Indicates the minor value of this version.
     */
    public function getMinor():int
    {
        return $this->RRR( ( $this->_value & 0x0F000000 ) , 24 ) ;
    }

    /**
     * Indicates the revision value of this version.
     */
    public function getRevision():int
    {
        return $this->_value & 0x0000FFFF;
    }

    /**
     * Returns a version representation.
     * @param string $value
     * @param string $separator
     * @return string|null
     */
    public static function fromString( string $value , string $separator = Char::DOT ) :?string
    {
        $v = new Version() ;

        if( $value == null || $value == "" )
        {
            return null ;
        }

        if( strpos( $value , $separator ) > -1 )
        {
            $values = explode( $separator , $value ) ;
            $len    = count( $values ) ;

            if( $len > 0 )
            {
                $v->setMajor( (int) $values[0] ) ;
            }

            if( $len > 1 )
            {
                $v->setMinor( (int) $values[1] ) ;
            }

            if( $len > 2 )
            {
                $v->setBuild( (int) $values[2] ) ;
            }

            if( $len > 3 )
            {
                $v->setRevision( (int) $values[3] ) ;
            }
        }
        else
        {
            $vv = (int) $value ;
            if( $vv != 0 )
            {
                $v->setMajor( $vv ) ;
            }
            else
            {
                $v = null ;
            }
        }

        return (string) $v ;
    }

    /**
     * Set the build value.
     */
    public function setBuild( int $value ):void
    {
        $this->_value = ( $this->_value & 0xFF00FFFF ) | ($value << 16) ;
    }

    /**
     * Set the major value.
     */
    public function setMajor( $value ):void
    {
        $this->_value = ( $this->_value & 0x0FFFFFFF ) | ( $value << 28 ) ;
    }

    /**
     * Set the minor value.
     */
    public function setMinor( $value ):void
    {
        $this->_value = ( $this->_value & 0xF0FFFFFF ) | ( $value << 24 ) ;
    }

    /**
     * Set the revision value.
     */
    public function setRevision( $value ):void
    {
        $this->_value = ( $this->_value & 0xFFFF0000 ) | $value;
    }

    /**
     * Returns the string representation of the object.
     * @return string The string representation of the object.
     */
    public function __toString() :string
    {
        $data =
        [
            $this->getMajor() ,
            $this->getMinor() ,
            $this->getBuild() ,
            $this->getRevision()
        ];

        if( ( $this->fields > 0) && ( $this->fields < 5 ) )
        {
            $data = array_slice( $data , 0 , $this->fields ) ;
        }
        else
        {
            $l = count($data);
            for( $i = $l-1 ; $i>0 ; $i-- )
            {
                if( $data[$i] == 0 )
                {
                    array_pop( $data ) ;
                }
                else
                {
                    break;
                }
            }
        }

        return implode( $this->separator , $data ) ;
    }

    /**
     * Returns the primitive value of the object.
     * @return int The primitive value of the object.
     */
    public function valueOf():int
    {
        return $this->_value ;
    }

    public function __get( $name ) :mixed
    {
        $method = null ;

        if( $name != '' )
        {
            $method = 'get' . ucfirst($name) ;
        }

        if( method_exists( $this, $method ) )
        {
            return $this->{ $method }();
        }

        trigger_error( 'Undefined property: ' . get_class( $this ) . '::$' . $name ) ;

        return 0 ;
    }

    public function __set( $name , $value )
    {
        $method = null ;

        if( $name != Char::EMPTY )
        {
            $method = 'set' . ucfirst( $name ) ;
        }

        if( method_exists( $this, $method ) )
        {
            $this->{ $method }( $value );
        }
        else
        {
            trigger_error( 'Undefined property: ' . get_class( $this ) . '::$' . $name ) ;
        }
    }

    private int $_value ;

    /**
     * Emulates the >>> binary operator.
     */
    private function RRR( $a , $b ) :int
    {
        return (int)( (float) $a / pow( 2 , (int) $b ) );
    }
}
