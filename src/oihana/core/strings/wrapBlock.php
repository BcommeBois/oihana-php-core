<?php

declare(strict_types=1);

namespace oihana\core\strings ;

/**
 * Wraps a block of lines with a header and footer line.
 *
 * @param array|string $lines          Array or string of lines to wrap.
 * @param string       $before         Line inserted before the block.
 * @param string       $after          Line inserted after the block.
 * @param string|int   $indent         Indentation for the inner block (default: '').
 * @param string       $separator      Line separator (defaults to PHP_EOL).
 * @param bool         $keepEmptyLines Whether to preserve empty lines (default: true).
 *
 * @return string Final wrapped block.
 *
 * @example
 * ```php
 * echo wrapBlock("line1\nline2", '{', '}', 4);
 * // Output:
 * // {
 * //     line1
 * //     line2
 * // }
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function wrapBlock( array|string $lines, string $before , string $after , string|int $indent = '' , string $separator = PHP_EOL , bool $keepEmptyLines = true  ): string
{
    $inner = block( $lines , $indent , $separator , $keepEmptyLines ) ;
    if ( $inner === '' )
    {
        return $before . $after;
    }
    return $before . $separator . $inner . $separator . $after ;
}