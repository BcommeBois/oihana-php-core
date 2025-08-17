<?php

namespace oihana\core\strings ;

use Stringable;

/**
 * Build a key-value expression like `key: value`.
 *
 * This function ensures that both key and value are cast to strings.
 *
 * @param string|object|int|float|bool $key       The key (stringable value).
 * @param mixed                        $value     The value (will be cast to string).
 * @param string                       $separator The separator between key and value (default ':').
 *
 * @return string The key-value expression.
 *
 * @example
 * ```php
 * echo keyValue( 'name' , 'Eka' );        // 'name:Eka'
 * echo keyValue( 'age' , 30, ' = ' );     // 'age=30'
 * echo keyValue( 'active' , true );       // 'active:true'
 * echo keyValue( 'tags' , ['php','js'] ); // 'tags:[php, js]'
 * echo keyValue( 'description' , null );  // 'description:null'
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function keyValue( string|object|int|float|bool $key , mixed $value , string $separator = ':' ) :string
{
    $keyStr   = (string) $key ;
    $valueStr = match ( true )
    {
        is_array($value) => '[' . implode(', ' , array_map(fn($v) => (string)$v, $value)) . ']' ,
        is_bool($value)  => $value ? 'true' : 'false' ,
        $value === null  => 'null' ,
        default          => (string) $value ,
    };

    return $keyStr . $separator . $valueStr;
}