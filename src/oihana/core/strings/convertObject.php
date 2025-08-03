<?php

namespace oihana\core\strings ;

use Closure;
use DateTimeInterface;

function convertObject( object $obj, array $options, int $level, array &$cache ): string
{
    $id = spl_object_hash( $obj ) ;

    if ( isset( $cache[ $id ] ) )
    {
        return "'<circular-ref>'";
    }

    if ( $options['maxDepth'] <= $level )
    {
        return "'<max-depth-reached>'";
    }

    $cache[ $id ] = true ;

    try
    {
        if ( $obj instanceof DateTimeInterface )
        {
            return 'new \\' . get_class($obj) . '(' .
                formatQuotedString($obj->format('c'), $options['quote'], $options['compact']) . ')';
        }

        if ( function_exists('enum_exists') && enum_exists( get_class( $obj ) ) )
        {
            return get_class($obj) . '::' . $obj->name ;
        }

        if ($obj instanceof Closure)
        {
            return "'<closure>'";
        }

        $vars = get_object_vars( $obj ) ;

        if (empty($vars)) {
            return '/* object(' . get_class($obj) . ') */ null';
        }

        $indent      = $options[ 'indent'      ] ?? '    ' ;
        $inline      = $options[ 'inline'      ] ?? false ;
        $useBrackets = $options[ 'useBrackets' ] ?? false ;

        $pad = str_repeat($indent, $level + 1);
        $endPad = str_repeat($indent, $level);
        $entries = [];

        foreach ( $vars as $k => $v )
        {
            $kStr = convert($k, $options, $level + 1, $cache);
            $vStr = convert($v, $options, $level + 1, $cache);
            $entries[] = $inline ? "$kStr => $vStr" : "$pad$kStr => $vStr";
        }

        $open = $useBrackets ? '(object)[' : '(object)array(';
        $close = $useBrackets ? ']' : ')';

        if ($inline) {
            return $open . implode(', ', $entries) . $close;
        }

        return $open . PHP_EOL . implode(',' . PHP_EOL, $entries) . PHP_EOL . $endPad . $close;

    }
    finally
    {
        unset( $cache[ $id ] ) ;
    }
}