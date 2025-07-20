# Oihana PHP - Core library

![Oihana Php Core](https://raw.githubusercontent.com/BcommeBois/oihana-php-core/main/.phpdoc/template/assets/images/oihana-php-core-logo-inline-512x160.png)

A lightweight and modular core library for modern PHP development.
Designed for clarity, extensibility, and performance, with a consistent, functional-style API.

## ✨ Features

The oihana/php-core library provides pure utility functions (no side effects), organized into logical, reusable packages:

### 🔢 Arrays (oihana\core\arrays)

Advanced array utilities:
- Access and mutation:
- get(), set(), delete(), exists()
- Transformations:
- flatten(), tail(), unique(), shuffle(), swap()
- toArray(), stub()
- Structure detection:
- isIndexed(), hasIntKeys(), hasStringKeys()

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
- Introspect callable/function definitions:
- getFunctionInfo()

### ✍️ Strings (oihana\core\strings)
String formatting, case conversions, and utilities:
- Case & slug:
- camel(), snake(), kebab(), hyphenate(), lower(), latinize()
- Format & identifiers:
- fastFormat(), formatRequestArgs(), urlencore(), toString()
- Validation:
- isRegexp(), luhn()
- Random generation:
- randomKey()

⚙️ Utils
- ifNull() — return a fallback if a value is null

## 📚 Documentation

Full project documentation is available at:

👉 https://bcommebois.github.io/oihana-php-core

## Installation and Usage

> **Requires [PHP 8.4+](https://php.net/releases/)**

Install via [Composer](https://getcomposer.org):

```bash
composer require oihana/php-core
```

Usage example: 
```php
use function oihana\core\arrays\get;

$array = [
    'user' => [
        'name' => 'Marc',
        'address' => [
            'city' => 'Paris',
            'geo' => ['lat' => 48.8566, 'lng' => 2.3522],
        ],
    ],
];

// Retrieve nested value via dot notation:
$city = get($array, 'user.address.city', 'Unknown');
echo $city; // Outputs: Paris
```

## ✅ Running Unit Tests

To run all tests:
```bash
composer run-script test
```

To run a specific test file:
```bash
composer run test ./tests/oihana/core/arrays/FlattenTest.php
```

## 🧾 Licence

This project is licensed under the [Mozilla Public License 2.0 (MPL-2.0)](https://www.mozilla.org/en-US/MPL/2.0/).

## 👤 About the author

 * Author : Marc ALCARAZ (aka eKameleon)
 * Mail : marc@ooop.fr
 * Website : http://www.ooop.fr
