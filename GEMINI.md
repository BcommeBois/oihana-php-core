# Gemini Project Configuration

This document provides guidelines for interacting with the `oihana/php-core` project.

## Project Summary

`oihana/php-core` is a lightweight, modular core library for modern PHP development. It offers a collection of pure, side-effect-free utility functions organized into logical namespaces:

-   `oihana\core\accessors`
-   `oihana\core\arrays`
-   `oihana\core\date`
-   `oihana\core\maths`
-   `oihana\core\numbers`
-   `oihana\core\objects`
-   `oihana\core\strings`

The library is designed with a focus on clarity, performance, and a consistent functional-style API. It is installable via Composer.

## Technical Requirements

-   **PHP:** 8.4 or newer
-   **PHPUnit:** 12 or newer

## Language and Communication

-   **Interaction Language:** Please communicate with me **exclusively in French**.
-   **Code and Documentation Language:** All generated code, code examples, PHPDoc blocks, comments, commit messages, and documentation files (including `.md` files) must be written **exclusively in English**.

## Coding Style and Conventions

Please adhere to the following conventions to maintain consistency with the existing codebase.

### General Principles

-   **File Structure:** Each function resides in its own file, named after the function (e.g., `camel.php` contains the `camel()` function).
-   **Autoloading:** When adding a new function, its file path must be added to the `autoload.files` array in `composer.json` to ensure it is loaded correctly.
-   **Namespaces:** All code is organized under the `oihana\core` namespace.
-   **Immutability:** Functions should be pure and have no side effects. They should return new values instead of modifying inputs by reference where possible.

### Naming Conventions

-   **Functions:** `camelCase` (e.g., `camel`, `flatten`).
-   **Variables:** `camelCase` (e.g., `$source`, `$result`).
-   **PHPUnit Test Classes:** `FunctionNameTest` (e.g., `CamelTest`, `FlattenTest`).
-   **PHPUnit Test Methods:** `testFeatureOrBehavior` (e.g., `testBasicCases`, `testNestedArray`).

### PHPDoc and Comments

-   Every function must have a comprehensive PHPDoc block.
-   Include a clear description of the function's purpose.
-   Use `@param` to describe each parameter, its type, and purpose.
-   Use `@return` to describe the return value and its type.
-   Provide one or more `@example` blocks demonstrating usage.
-   Include `@package`, `@author`, and `@since` tags.
-   All documentation must be in **English**.

```php
/**
 * Converts a string to camelCase format.
 *
 * @param ?string $source      The input string to convert.
 * @param array   $separators  An array of separator characters.
 *
 * @return string The camelCase representation of the input string.
 *
 * @example
 * ```php
 * echo camel('hello_world'); // Outputs: helloWorld
 * ```
 *
 * @package oihana\core\strings
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function camel(?string $source, array $separators = ["_", "-"]): string
{
    // function body
}
```

### Typing

-   Use strict type hints for all function arguments and return types (e.g., `?string`, `array`, `: bool`).
-   Enable `strict_types=1` where applicable.

### Code Style

-   **Control Structures:** Use spaces around conditions and place braces on new lines.
    ```php
    if (is_array($item)) {
        // ...
    }
    ```
-   **Arrays:** Use the short array syntax `[]`.
-   **Tests:**
    -   Extend `PHPUnit\Framework\TestCase`.
    -   Use specific assertions like `assertSame()` for strict comparisons and `assertEquals()` for value comparisons.

```