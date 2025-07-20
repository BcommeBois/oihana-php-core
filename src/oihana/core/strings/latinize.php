<?php

namespace oihana\core\strings ;

use oihana\core\strings\helpers\LatinConverter;

/**
 * Converts a string by replacing accented Latin characters with their ASCII equivalents.
 *
 * This method transliterates accented characters such as 'é', 'ü', or 'ñ'
 * into their closest ASCII counterparts ('e', 'u', 'n').
 *
 * @param string $source The input string containing accented Latin characters.
 * @return string The transliterated string with ASCII characters only.
 *
 * @example
 * ```php
 * echo latinize('Café Münsterländer'); // Outputs: Cafe Munsterlander
 * echo latinize('¡Hola señor!');      // Outputs: ¡Hola senor!
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function latinize( string $source = '' ): string
{
    return strtr( $source , LatinConverter::LATINS );
}