# Oihana PHP Core library - Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Added
 - Add the oihana/core/normalize function : Normalizes a value according to the given cleaning flags.
 - **Accessors:**
   - Add the `deleteKeyValues()` function + add in the `deleteKeyValue()` function the array|string $key parameter (multiple keys deletion)
 - **Callables:**
   - Add the `isCallable()` and `resolveCallable()` functions
   - Add the `chainCallables()`, `memoizeCallable()`, `middlewareCallable()` and `wrapCallable()` functions
 - **JSON:**
   - Add the `getJsonType()` function 
 - **Strings:**
   - Add the `uniqueKey()` function
   - Add the `replacePathPlaceholders()` function 

### Changed
- **Arrays:** In `clean()`, add the `CleanFlag::RETURN_NULL` option.

## [1.0.6] - 2025-09-27

### Added
- **Core:**
  - Add `isLiteral()` function.
- **Accessors:**
  - Add `assertDocumentKeyValid()`, `deleteKeyValue()`, `getKeyValue()`, `hasKeyValue()`, `resolveReferencePath()`, and `setKeyValue()` for unified data access.
- **Arrays:**
  - Add `CleanFlag` enum for `clean()` function.
  - Add `clean()`, `ensureArrayPath()`, `getFirstKey()`, and `getFirstValue()` helpers.
- **Date:**
  - Add `formatDateTime()` and `now()` functions.
- **Documents:**
  - Add `formatDocument()`, `formatDocumentWith()`, and `resolvePlaceholders()` for templating.
- **Env:**
  - Add a comprehensive suite of environment detection helpers (`cpuCount()`, `isCli()`, `isDocker()`, `isMac()`, `phpVersion()`, etc.).
- **Helpers:**
  - Add `conditions()` helper.
- **JSON:**
  - Add `deepJsonSerialize()`, `isValidJsonDecodeFlags()`, and `isValidJsonEncodeFlags()` for advanced JSON handling.
- **Maths:**
  - Add `bearing()`, `fixAngle()`, `gcd()`, and `haversine()` for mathematical and geolocation calculations.
- **Objects:**
  - Add `ensureObjectPath()`, `hasAllProperties()`, `hasAnyProperty()`, `setObjectValue()`, and `toAssociativeArray()`.
- **Options:**
  - Add `CompressOption` enum.
- **Strings:**
  - Add numerous string manipulation and formatting functions (`append()`, `between()`, `block()`, `compile()`, `dotKebab()`, `format()`, `isRegexp()`, `pad()`, `slice()`, `wrapBlock()`, etc.).
- **Interfaces:**
  - Add `Arrayable`, `ClearableArrayable`, `Cloneable`, and `Equatable` interfaces.

### Changed
- **Arrays:** In `compress()`, add the `removeKeys` option.
- **Objects:** In `compress()`, add the `removeKeys` option.

### Removed
- Remove `oihana\exceptions\ExceptionTrait`.
- **Core:** The folder structure was refactored to only keep `enums`, `exceptions`, `interfaces`, `reflections` at the top level.

## [1.0.5] - 2025-07-10

### Added
- **Arrays:**
  - Add `flatten()`, `inBetween()`, `shuffle()`, `stub()`, `swap()`, and `tail()` functions.
- **Strings:**
  - Add string case helpers: `camel()`, `kebab()`, `hyphenate()`, `snake()`.
  - Add `toString()` function and `SnakeCache` helper.
- **Exceptions:**
  - Add `DirectoryException` and `MissingPassphraseException`.
- **Date:**
  - Add `DateTrait`.
- **Files:**
  - Add `OpenSSLFileEncryption`.

### Changed
- Rename `oihana\core\files\loadAndMergeArrayFiles` to `loadAndMergeArrayFromPHPFiles`.

### Removed
- Remove `oihana\core\strings\toCamelCase` (replaced by `camel()`).

## [1.0.4] - 2025-07-03

### Added
- Add `oihana\core\arrays\unique()` function.

## [1.0.3] - 2025-06-29

### Added
- **Reflections:**
  - Add `ConstantException`.
  - Add `ReflectionTrait::hydrate()` and `ReflectionTrait::jsonSerializeFromPublicProperties()`.
  - Add unit tests for `Version` class.

### Changed
- **Reflections:**
  - Update `Version` class to use PHP 8.4 hooks with build, major, minor, and revision properties.

## [1.0.2] - 2025-06-20

### Added
- **Arrays:**
  - Add `deepMerge()` function.
- **Files:**
  - Add `loadAndMergeArrayFiles()` and `recursiveFilePaths()` helpers.
- **Logging:**
  - Add `EmojiProcessor` and `SymbolProcessor` for Monolog.

## [1.0.1] - 2025-06-17

### Added
- **Date:**
  - Add `TimeInterval` class.
- **Enums:**
  - Add `ArithmeticOperator`, `Boolean`, `Char`, `CharacterSet`, `JsonParam`, `Order`, and `Param`.
- **Exceptions:**
  - Add `ExceptionTrait`, `FileException`, `ResponseException`, `UnsupportedOperationException`, `ValidationException`.
  - Add HTTP exceptions: `Error403`, `Error404`, `Error500`.
- **Interfaces:**
  - Add `Equatable` interface.
- **Logging:**
  - Add `Logger` and `LoggerTrait`.
- **Reflections:**
  - Add `Reflection` and `Version` classes.
  - Add `ConstantTrait` and `ReflectionTrait`.
- **Traits:**
  - Add `KeyValueTrait`, `ToStringTrait`, `UnsupportedTrait`, and `UriTrait`.

## [1.0.0] - 2025-06-16

### Added
- Initial release of the library.
- **Core:**
  - Add `isNull()` function.
- **Arrays:**
  - Add `compress()`, `delete()`, `exists()`, `get()`, `isAssociative()`, `removeKeys()`, `set()`, and `toArray()`.
- **Date:**
  - Add `isDate()` and `isValidTimezone()`.
- **Maths:**
  - Add `ceilValue()`, `floorValue()`, and `roundValue()`.
- **Numbers:**
  - Add `clip()` function.
- **Objects:**
  - Add `compress()` function.
- **Strings:**
  - Add `fastFormat()`, `formatRequestArgs()`, `latinize()`, `luhn()`, `randomKey()`, `toCamelCase()`, and `urlencode()`.
- **Enums, Exceptions, and Traits:**
  - Add initial set of enums, exceptions, and traits for core functionality.
