<?php

namespace oihana\core\strings ;

/**
 * Converts a string to a kebab cases string.
 *
 * @param ?string $source The string expression to format.
 * @return string The kebab-case string.
 *
 * @example
 * echo kebab("helloWorld"); // Outputs: "hello-world"
 */
function kebab( ?string $source): string
{
    return snake( $source , '-' ) ;
}