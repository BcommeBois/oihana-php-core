<?php

declare(strict_types=1);

namespace oihana\core\strings;

/**
 * Wrap a string with a given character.
 *
 * @param string $value The value to wrap.
 * @param string $char  The character to wrap with.
 *
 * @return string The wrapped value.
 *
 * @example
 * ```php
 * echo wrap('column'); // `column`
 * echo wrap('`column`'); // `\`column\``
 * echo wrap('column', '"'); // "column"
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function wrap( string $value, string $char = '`' ): string
{
    return $char . addcslashes( $value , $char ) . $char;
}

