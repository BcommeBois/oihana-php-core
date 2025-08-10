# Oihana PHP - Core library

![Oihana Php Core](https://raw.githubusercontent.com/BcommeBois/oihana-php-core/main/.phpdoc/template/assets/images/oihana-php-core-logo-inline-512x160.png)

A lightweight and modular core library for modern PHP development.
Designed for clarity, extensibility, and performance, with a consistent, functional-style API.

## 📚 Documentation

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

## ✨ Features

The oihana/php-core library provides pure utility functions (no side effects), organized into logical, reusable packages:

### 🧾 [Accessors](https://github.com/BcommeBois/oihana-php-core/wiki/oihana%E2%80%90core%E2%80%90accessors) (oihana\core\accessors)

Unified access for both arrays and objects:
- Read: getKeyValue()
- Write: setKeyValue()
- Delete: deleteKeyValue() (supports wildcards: *, foo.bar.*)
- Exists: hasKeyValue()
- Validation and traversal: assertDocumentKeyValid(), resolveReferencePath()

Designed for safely accessing and modifying deep nested structures with dot notation support and automatic path creation.

### 🔢 Arrays (oihana\core\arrays)

Advanced array utilities:
- Access and mutation: get(), set(), delete(), exists()
- Transformations: flatten(), tail(), unique(), shuffle(), swap(), toArray(), stub()
- Structure detection: isIndexed(), hasIntKeys(), hasStringKeys()

### 📅 Date (oihana\core\date)

Date manipulation and validation:
- formatDateTime()
- isDate(), isValidTimezone()

### ➗ Maths (oihana\core\maths)
Smart numeric rounding helpers:
- ceilValue(), floorValue(), roundValue()

### 🔢 Numbers (oihana\core\numbers)
- Range clamping: clip()

### 🧱 Objects (oihana\core\objects)
Lightweight object manipulation:
- compress() — remove null/empty values
- set() — deep set a value in a nested structure

### 🧠 Reflections (oihana\core\reflections)
Introspect callable/function definitions:
- getFunctionInfo()

### ✍️ Strings (oihana\core\strings)
String formatting, case conversions, and utilities:
- Case & slug: camel(), snake(), kebab(), hyphenate(), lower(), latinize()
- Format & identifiers: fastFormat(), formatRequestArgs(), urlencode(), toString()
- Validation: isRegexp(), luhn()
- Random generation: randomKey()

### ⚙️ Utils
- ifNull() — return a fallback if a value is null

## 🚀 Quick Start

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

## 🧪 Examples

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

### Enums and constants helpers
Arithmetic operators as constants:
```php
use oihana\enums\ArithmeticOperator;

$expr  = '3 ' . ArithmeticOperator::ADDITION . ' 4'; // "3 + 4"
$power = '2 ' . ArithmeticOperator::EXPONENT . ' 8'; // "2 ** 8"
```

JSON parameters with defaults and validation:
```php
use oihana\enums\JsonParam;

$options = [
    JsonParam::ASSOCIATIVE => true,
    JsonParam::DEPTH       => 512,
    JsonParam::FLAGS       => JSON_PRETTY_PRINT,
];

$defaults = JsonParam::getDefaultValues();  // ['associative' => false, 'depth' => 512, 'flags' => 0]
JsonParam::isValidFlags(JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR); // true
```

php.ini option names with reflection helpers (from ConstantsTrait):
```php
use oihana\enums\IniOptions;

$all     = IniOptions::enums();                // sorted list of all option names
$exists  = IniOptions::includes('display_errors'); // true
$name    = IniOptions::getConstant('memory_limit'); // 'MEMORY_LIMIT'
IniOptions::validate('upload_max_filesize');   // throws if invalid
```

### Using ConstantsTrait directly in your own enum-like classes
```php
namespace App;

use oihana\reflections\traits\ConstantsTrait;

class Status
{
    use ConstantsTrait;

    public const string DRAFT     = 'draft';
    public const string PUBLISHED = 'published';
    public const string ARCHIVED  = 'archived';
}

// Usage
Status::enums();                 // ['archived','draft','published'] (sorted)
Status::includes('draft');       // true
Status::getConstant('published'); // 'PUBLISHED'
Status::validate('invalid');     // throws ConstantException
```

## ✅ Running Unit Tests

To run all tests:
```shell
$ composer test
```

To run a specific test file:
```shell
$ composer test tests/oihana/core/arrays/FlattenTest.php
```

## 🧾 License
This project is licensed under the [Mozilla Public License 2.0 (MPL-2.0)](https://www.mozilla.org/en-US/MPL/2.0/).

## 👤 About the author
- Author : Marc ALCARAZ (aka eKameleon)
- Mail : [marc@ooop.fr](mailto:marc@ooop.fr)
- Website : http://www.ooop.fr
