<?php

namespace oihana\core\strings ;

function convert( mixed $value , array $options , int $level , array &$cache ) :string
{
    if ( is_resource( $value ) )
    {
        $type = get_resource_type( $value ) ;
        if ( $type === 'Unknown' )
        {
            return "'<closed resource>'" ;
        }
        return "'<resource of type " . $type . ">'";
    }

    $type = gettype( $value ) ;

    $compact       = $options[ 'compact'       ] ?? false    ;
    $humanReadable = $options[ 'humanReadable' ] ?? false    ;
    $quote         = $options[ 'quote'         ] ?? 'single' ;

    if ( $humanReadable && in_array( $type, [ 'string' , 'boolean' , 'double' , 'integer' ] ) )
    {
        return toHumanReadableScalar( $value, $quote, $compact ) ;
    }

    return match ( $type )
    {
        'string'             => formatQuotedString( $value , $quote , $compact ) ,
        'boolean'            => $value ? 'true' : 'false',
        'integer' , 'double' => var_export( $value , true ) ,
        'array'              => convertArray  ( $value , $options , $level , $cache ) ,
        'object'             => convertObject ( $value , $options , $level , $cache ) ,
        default              => 'null',
    };
}