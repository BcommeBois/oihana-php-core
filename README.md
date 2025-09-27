# Oihana PHP - Core library

![Oihana Php Core](https://raw.githubusercontent.com/BcommeBois/oihana-php-core/main/.phpdoc/template/assets/images/oihana-php-core-logo-inline-512x160.png)

A lightweight and modular core library for modern PHP development.
Designed for clarity, extensibility, and performance, with a consistent, functional-style API.

[![Latest Version](https://img.shields.io/packagist/v/oihana/php-core.svg?style=flat-square)](https://packagist.org/packages/oihana/php-core)  
[![Total Downloads](https://img.shields.io/packagist/dt/oihana/php-core.svg?style=flat-square)](https://packagist.org/packages/oihana/php-core)  
[![License](https://img.shields.io/packagist/l/oihana/php-core.svg?style=flat-square)](LICENSE)

## Table of Contents

- [✨ Core Packages](#core-packages)
- [📚 Documentation](#documentation)
- [📦️ Installation](#installation)
- [💡 Features](#features)
- [🚀 Quick Start](#quick-start)
- [🧪 Examples](#examples)
- [✅ Running Unit Tests](#running-unit-tests)
- [🤝 Contributing](#contributing)
- [🧾 License](#license)
- [👤 About the author](#about-the-author)

## Core Packages

The library provides a suite of pure, side-effect-free utility functions organized into logical namespaces. Here’s a summary of what each package offers:

| Namespace | Description |
| :--- | :--- |
| `oihana\core\accessors` | Provides unified functions (`get`, `set`, `has`, `delete`) to safely access and manipulate nested data in both arrays and objects using dot notation. |
| `oihana\core\arrays` | A rich suite of utilities for array manipulation, including transformations, access, and structural analysis. |
| `oihana\core\date` | Helpers for date formatting and validation. |
| `oihana\core\documents` | Utilities for placeholder resolution and document formatting. |
| `oihana\core\env` | Functions to detect the current environment (CLI, Docker, OS) and PHP settings. |
| `oihana\core\json` | Helpers for advanced JSON serialization and flag validation. |
| `oihana\core\maths` | Functions for smart numeric rounding operations (ceil, floor, round). |
| `oihana\core\numbers` | Utilities for handling numbers, such as clamping a value within a specific range. |
| `oihana\core\objects` | Lightweight helpers for object manipulation, like compressing and deep-setting values. |
| `oihana\core\options` | Provides Enums for configuring function behaviors (e.g., `CompressOption`). |
| `oihana\core\reflections`| Provides reflection utilities to inspect functions and their properties. |
| `oihana\core\strings` | A comprehensive set of tools for string formatting, case conversion, validation, and generation. |
| `oihana\core` (Utils) | General-purpose utilities, such as `ifNull()` and `isLiteral()`. |

## Documentation

Full project documentation is available at:
👉 https://bcommebois.github.io/oihana-php-core

- Changelog: [CHANGELOG.md](./CHANGELOG.md)
- License: [MPL-2.0](./LICENSE)

## Installation

> **Requires [PHP 8.4+](https://php.net/releases/)**

Install via [Composer](https://getcomposer.org):

```bash
composer require oihana/php-core
```

## Features

The oihana/php-core library provides pure utility functions (no side effects), organized into logical, reusable packages:

### 🧾 Accessors (`oihana\core\accessors`)

Unified access for both arrays and objects:
- Read: `getKeyValue()`
- Write: `setKeyValue()`
- Delete: `deleteKeyValue()` (supports wildcards: `*`, `foo.bar.*`)
- Exists: `hasKeyValue()`
- Validation and traversal: `assertDocumentKeyValid()`, `resolveReferencePath()`

### 🔢 Arrays (`oihana\core\arrays`)

Advanced array utilities:
- Access and mutation: `get()`, `set()`, `delete()`, `exists()`
- Transformations: `flatten()`, `tail()`, `unique()`, `shuffle()`, `swap()`, `toArray()`, `stub()`
- Structure detection: `isIndexed()`, `hasIntKeys()`, `hasStringKeys()`

### 📅 Date (`oihana\core\date`)

Date manipulation and validation:
- `formatDateTime()`
- `isDate()`, `isValidTimezone()`
- `now()`

### 📄 Documents (`oihana\core\documents`)

Template and placeholder resolution:
- `formatDocument()`
- `resolvePlaceholders()`

### 💻 Env (`oihana\core\env`)

Environment detection helpers:
- OS detection: `isLinux()`, `isMac()`, `isWindows()`
- Environment type: `isCli()`, `isWeb()`, `isDocker()`
- PHP info: `phpVersion()`, `isExtensionLoaded()`

### 🧬 JSON (`oihana\core\json`)

Advanced JSON utilities:
- `deepJsonSerialize()`
- `isValidJsonEncodeFlags()`, `isValidJsonDecodeFlags()`

### ➗ Maths (`oihana\core\maths`)

Smart numeric rounding helpers:
- `ceilValue()`, `floorValue()`, `roundValue()`
- Geolocation: `haversine()`, `bearing()`

### 🔢 Numbers (`oihana\core\numbers`)

- Range clamping: `clip()`

### 🧱 Objects (`oihana\core\objects`)

Lightweight object manipulation:
- `compress()` — remove null/empty values
- `set()` — deep set a value in a nested structure
- `toAssociativeArray()`

### ⚙️ Reflections (`oihana\core\reflections`)

- Function analysis: `getFunctionInfo()`

### ✍️ Strings (`oihana\core\strings`)

String formatting, case conversions, and utilities:
- Case & slug: `camel()`, `snake()`, `kebab()`, `hyphenate()`, `lower()`, `latinize()`
- Format & identifiers: `fastFormat()`, `formatRequestArgs()`, `urlencode()`, `toString()`
- Validation: `isRegexp()`, `luhn()`
- Random generation: `randomKey()`

### 🛠️ Utils (`oihana\core`)

General-purpose helpers:
- `ifNull()` — return a fallback if a value is null
- `isLiteral()` — check if a value is a literal

## Quick Start

Most helpers are loaded via Composer autoload. You can import functions directly using `use function` and call them.

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use function oihana\core\strings\camel;
use function oihana\core\strings\fastFormat;
use function oihana\core\arrays\get;
use function oihana\core\accessors\getKeyValue;
use function oihana\core\accessors\setKeyValue;

// Strings
echo camel('hello_world');                 // helloWorld
echo fastFormat('Hello {0}', 'World');     // Hello World

// Arrays (dot-notation path)
$profile = ['user' => ['name' => 'Alice', 'city' => 'Paris']];
echo get($profile, 'user.name');           // Alice

// Accessors work with arrays and objects
$doc = (object)['user' => (object)['email' => 'a@b.c']];
echo getKeyValue($doc, 'user.email');      // a@b.c
$doc = setKeyValue($doc, 'user.age', 30);  // adds nested property safely
```

## Examples

### Accessors: work with arrays and objects
```php
use function oihana\core\accessors\getKeyValue;
use function oihana\core\accessors\setKeyValue;

$doc = ['user' => ['name' => 'Alice']];
echo getKeyValue($doc, 'user.name');            // Alice
$doc = setKeyValue($doc, 'user.age', 30);       // ['user' => ['name' => 'Alice', 'age' => 30]]

$obj = (object)['user' => (object)['email' => 'a@b.c']];
echo getKeyValue($obj, 'user.email');           // a@b.c
$obj = setKeyValue($obj, 'user.active', true);  // adds nested object structure
```

### Arrays: reading and transforming
```php
use function oihana\core\arrays\get;
use function oihana\core\arrays\flatten;
use function oihana\core\arrays\unique;

$data = ['a' => 1, 'b' => ['c' => 2, 'd' => [3, 4]]];
echo get($data, 'b.c');               // 2
print_r(flatten($data));              // ['a' => 1, 'b.c' => 2, 'b.d.0' => 3, 'b.d.1' => 4]
print_r(unique([1,1,2,3,3]));         // [1,2,3]
```

### Strings: formatting and cases
```php
use function oihana\core\strings\camel;
use function oihana\core\strings\fastFormat;

echo camel('foo-bar_baz');                        // fooBarBaz
echo fastFormat('User {0} has {1} points', 'Alice', 1500);
// User Alice has 1500 points
```

### Objects: cleaning data
```php
use function oihana\core\objects\compress;

$user = (object)[
    'name' => 'John',
    'email' => 'john@example.com',
    'phone' => null,
    'address' => ''
];

// Removes all null and empty string values
print_r(compress($user));
/*
stdClass Object
(
    [name] => John
    [email] => john@example.com
)
*/
```

### Date: formatting
```php
use function oihana\core\date\formatDateTime;

echo formatDateTime('2023-10-27 10:00:00', 'd/m/Y H:i'); // 27/10/2023 10:00
```

### Numbers: clamping a value
```php
use function oihana\core\numbers\clip;

echo clip(15, 0, 10); // 10
echo clip(-5, 0, 10); // 0
echo clip(5, 0, 10);  // 5
```

## Running Unit Tests

To run all tests:
```shell
$ composer test
```

To run a specific test file:
```shell
$ composer test tests/oihana/core/arrays/FlattenTest.php
```

## Contributing

Contributions are welcome! Whether you're fixing a bug, improving an existing feature, or proposing a new one, your help is appreciated.

Please feel free to:
- **Report a bug:** If you find a bug, please open an issue and provide as much detail as possible.
- **Suggest an enhancement:** Have an idea to make this library better? Open an issue to discuss it.
- **Submit a pull request:** Fork the repository, make your changes, and open a pull request. Please ensure all tests are passing before submitting.

You can find the issues page here: [https://github.com/BcommeBois/oihana-php-core/issues](https://github.com/BcommeBois/oihana-php-core/issues)

## License
This project is licensed under the [Mozilla Public License 2.0 (MPL-2.0)](https://www.mozilla.org/en-US/MPL/2.0/).

## About the author
- Author : Marc ALCARAZ (aka eKameleon)
- Mail : [marc@ooop.fr](mailto:marc@ooop.fr)
- Website : http://www.ooop.fr
