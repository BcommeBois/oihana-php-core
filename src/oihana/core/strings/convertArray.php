<?php

namespace oihana\core\strings ;

function convertArray(array $array, array $options , int $level, array &$cache ): string
{
    if ( $options['maxDepth'] <= $level ) 
    {
        return "'<max-depth-reached>'";
    }

    $indent      = $options[ 'indent'      ] ?? '    ' ;
    $inline      = $options[ 'inline'      ] ?? false ;
    $useBrackets = $options[ 'useBrackets' ] ?? false ;

    $pad     = str_repeat( $indent , $level + 1 ) ;
    $endPad  = str_repeat( $indent , $level ) ;
    $entries = [] ;

    $isSequential = array_is_list( $array ) ; // PHP 8.1+ ou fonction custom

    foreach ( $array as $k => $v )
    {
        $vStr = convert( $v , $options , $level + 1 , $cache ) ;

        if ( $isSequential )
        {
            $entries[] = $inline ? $vStr : "$pad$vStr";
        }
        else
        {
            $kStr      = convert( $k , $options , $level + 1 , $cache ) ;
            $entries[] = $inline ? "$kStr => $vStr" : "$pad$kStr => $vStr";
        }
    }

    $open  = $useBrackets ? '[' : 'array(' ;
    $close = $useBrackets ? ']' : ')'      ;

    if ( $inline )
    {
        return $open . implode(', ', $entries) . $close;
    }

    if ( empty( $entries ) )
    {
        return $open . $close;
    }

    return $open . PHP_EOL . implode(',' . PHP_EOL, $entries) . PHP_EOL . $endPad . $close;
}