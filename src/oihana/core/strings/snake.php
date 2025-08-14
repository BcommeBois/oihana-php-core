<?php

namespace oihana\core\strings ;

use oihana\core\strings\helpers\SnakeCache;

/**
 * Converts a string to snake_case (or a custom delimiter).
 *
 * This function transforms camelCase, PascalCase, and space-separated words
 * into snake case or any delimiter specified.
 *
 * It uses an internal cache (via `SnakeCache`) to optimize repeated calls with the same input.
 * The cache can be flushed by calling `SnakeCache::flush()`.
 *
 * @param ?string $source The input string to convert.
 * @param  string $delimiter The delimiter to use (default is underscore '_').
 * @param ?string $encoding The encoding parameter is the character encoding. If it is omitted or null, the internal character encoding value will be used.
 *
 * @return string The converted snake_case (or custom delimiter) string.
 *
 * @example
 * Basic usage
 * ```php
 * echo snake("helloWorld");  // Outputs: "hello_world"
 * echo snake("HelloWorld");  // Outputs: "hello_world"
 * echo snake("Hello World"); // Outputs: "hello_world"
 * echo snake("helloworld");  // Outputs: "helloworld"
 * ```
 * With numbers
 * ```php
 * echo snake("helloWorld123"); // Outputs: "hello_world123"
 * echo snake("UserID42");      // Outputs: "user_id_42"
 * ```
 *
 * Unicode support
 * ```php
 * echo snake("helloWorldCafé"); // Outputs: "hello_world_café"
 * echo snake("CaféAuLait");     // Outputs: "café_au_lait"
 * echo snake("NaïveBayesian");  // Outputs: "naïve_bayesian"
 * echo snake("Emoji😊Test");    // Outputs: "emoji_😊_test"
 * ```
 *
 * Complex cases
 * ```php
 * echo snake("MyXMLParser");  // Outputs: "my_xml_parser"
 * echo snake("JSONToArray");  // Outputs: "json_to_array"
 * echo snake("hello world how are you"); // Outputs: "hello_world_how_are_you"
 * ```
 *
 * Custom delimiters
 * ```php
 * echo snake("helloWorld", "-"); // Outputs: "hello-world"
 * echo snake("helloWorld", "|"); // Outputs: "hello|world"
 * echo snake("helloWorld", " "); // Outputs: "hello world"
 * ```
 *
 * Edge cases
 * ```php
 * echo snake("");   // Outputs: ""
 * echo snake(null); // Outputs: ""
 * ```
 *
 * Clear the internal cache if needed
 * ```php
 * use oihana\core\strings\helpers\SnakeCache;
 * SnakeCache::flush();
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function snake( ?string $source , string $delimiter = '_' , ?string $encoding = 'UTF-8' ) : string
{
    if (!is_string($source) || $source === '')
    {
        return '';
    }

    $key = $source;

    if ( SnakeCache::has( $key , $delimiter ) )
    {
        return SnakeCache::get( $key , $delimiter ) ;
    }

    // Replace spaces with delimiter
    $source = preg_replace('/\s+/u', $delimiter, $source);

    // Separate camelCase / PascalCase
    $source = preg_replace('/([\p{Ll}])([\p{Lu}])/u', '$1' . $delimiter . '$2', $source);
    $source = preg_replace('/([\p{Lu}]+)([\p{Lu}][\p{Ll}])/u', '$1' . $delimiter . '$2', $source);

    // Separate sequences of 2+ uppercase letters followed by numbers (like "ID42")
    $source = preg_replace('/([\p{Lu}]{2,})(\p{N}+)/u', '$1' . $delimiter . '$2', $source);

    // Do NOT separate numbers at the end of a word (like "helloWorld123")

    // Separate non-alphanumeric characters (emojis, symbols) with delimiter
    $source = preg_replace('/([^\p{L}\p{N}' . preg_quote($delimiter, '/') . ']+)/u', $delimiter . '$1' . $delimiter, $source);

    // Clean up duplicate delimiters
    $source = preg_replace('/' . preg_quote($delimiter, '/') . '+/u', $delimiter, $source);

    // Remove delimiters at the beginning and end
    $source = trim($source, $delimiter);

    // Convert everything to lowercase
    $source = mb_strtolower($source, $encoding);

    SnakeCache::set($key, $delimiter, $source);

    return $source;
}