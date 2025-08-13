# Oihana PHP Core library - Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Added
- Adds oihana\core\isLiteral
- Adds oihana\core\accessors\assertDocumentKeyValid
- Adds oihana\core\accessors\deleteKeyValue
- Adds oihana\core\accessors\getKeyValue
- Adds oihana\core\accessors\hasKeyValue
- Adds oihana\core\accessors\setKeyValue
 
- Adds oihana\core\date\formatDateTime

- Adds oihana\core\documents\formatDocument
- Adds oihana\core\documents\formatDocumentWith
- Adds oihana\core\documents\resolvePlaceholders

- Adds oihana\core\strings\append
- Adds oihana\core\strings\block
- Adds oihana\core\strings\blockPrefix
- Adds oihana\core\strings\blockSuffix
- Adds oihana\core\strings\format
- Adds oihana\core\strings\formatFromDocument
- Adds oihana\core\strings\isRegexp
- Adds oihana\core\strings\pad
- Adds oihana\core\strings\padBoth
- Adds oihana\core\strings\padEnd
- Adds oihana\core\strings\padStart
- Adds oihana\core\strings\prepend
- Adds oihana\core\strings\slice
- Adds oihana\core\strings\toPhpString
- Adds oihana\core\strings\wrapBlock

- Adds oihana\exceptions\BindException
- Adds oihana\exceptions\ExceptionTrait
- Adds oihana\exceptions\MissingPassphraseException
- Adds oihana\exceptions\ResponseException
- Adds oihana\exceptions\UnsupportedOperationException
- Adds oihana\exceptions\ValidationException
- 
- Adds oihana\exceptions\http\Error403
- Adds oihana\exceptions\http\Error404
- Adds oihana\exceptions\http\Error500

- Adds oihana\interfaces\Arrayable
- Adds oihana\interfaces\ClearableArrayable
- Adds oihana\interfaces\Cloneable
- Adds oihana\interfaces\Equatable
- Adds oihana\interfaces\Optionable

- Adds oihana\reflections\attributes\HydrateAs
- Adds oihana\reflections\attributes\HydrateKey
- Adds oihana\reflections\attributes\HydrateWith
- Adds oihana\reflections\exceptions\ConstantException
- Adds oihana\reflections\traits\ConstantsTrait
- Adds oihana\reflections\Reflection
- Adds oihana\reflections\Version

### Removed
- Core folder only and keep : enums, exceptions, interfaces, reflections 

### Changed
- Rename oihana\core\files\loadAndMergeArrayFromPHPFiles -> oihana\files\requireAndMergeArrays

## [1.0.5] - 2025-07-10

### Added
- Adds oihana\core\arrays\flatten
- Adds oihana\core\arrays\inBetween
- Adds oihana\core\arrays\shuffle
- Adds oihana\core\arrays\stub
- Adds oihana\core\arrays\swap
- Adds oihana\core\arrays\tail
- Adds oihana\core\strings\camel
- Adds oihana\core\strings\kebab
- Adds oihana\core\strings\hyphenate
- Adds oihana\core\strings\snake
- Adds oihana\core\strings\helpers\SnakeCache
- Adds oihana\core\strings\toString
- Adds oihana\exceptions\DirectoryException
- Adds oihana\exceptions\MissingPassphraseException

- Adds oihana\date\traits\DateTrait
- Adds oihana\files\OpenSSLFileEncryption

### Changed
- Rename oihana\core\files loadAndMergeArrayFiles -> loadAndMergeArrayFromPHPFiles

### Removed
- Adds oihana\core\strings\toCamelCase

## [1.0.4] - 2025-07-03
**### Added
- Adds oihana\core\arrays\unique**

## [1.0.3] - 2025-06-29
### Added
- Adds oihana\reflections\exceptions\ConstantException
- Adds oihana\reflections\ReflectionTrait::hydrate
- Adds oihana\reflections\ReflectionTrait::jsonSerializeFromPublicProperties
- Change oihana\reflections\Version : Unit tests
- 
### Changed
- Change oihana\reflections\Version : use PHP 8.4 hooks with the build, major, minor and revision properties.

## [1.0.2] - 2025-06-20
- Adds oihana\core\arrays\deepMerge
- Adds oihana\core\files\loadAndMergeArrayFiles
- Adds oihana\core\files\recursiveFilePaths
- Adds oihana\logging\monolog\processors\EmojiProcessor
- Adds oihana\logging\monolog\processors\SymbolProcessor

## [1.0.1] - 2025-06-17

### Added

- Adds oihana\date\TimeInterval
- Adds oihana\enums\ArithmeticOperator
- Adds oihana\enums\Boolean
- Adds oihana\enums\Char
- Adds oihana\enums\CharacterSet
- Adds oihana\enums\JsonParam
- Adds oihana\enums\Order
- Adds oihana\enums\Param
- Adds oihana\exceptions\ExceptionTrait
- Adds oihana\exceptions\FileException
- Adds oihana\exceptions\ResponseException
- Adds oihana\exceptions\UnsupportedOperationException
- Adds oihana\exceptions\ValidationException
- Adds oihana\exceptions\http\Error403
- Adds oihana\exceptions\http\Error404
- Adds oihana\exceptions\http\Error500
- Adds oihana\interfaces\Equatable
- Adds oihana\logging\Logger
- Adds oihana\logging\LoggerTrait
- Adds oihana\reflections\Reflection
- Adds oihana\reflections\Version
- Adds oihana\reflections\traits\ConstantTrait
- Adds oihana\reflections\traits\ReflectionTrait
- Adds oihana\traits\KeyValueTrait
- Adds oihana\traits\ToStringTrait
- Adds oihana\traits\UnsupportedTrait
- Adds oihana\traits\UriTrait

## [1.0.0] - 2025-06-16

### Added

- Adds oihana\core\isNull
- Adds oihana\core\arrays\compress
- Adds oihana\core\array\delete
- Adds oihana\core\array\exists
- Adds oihana\core\array\get
- Adds oihana\core\array\isAssociative
- Adds oihana\core\array\removeKeys
- Adds oihana\core\array\set
- Adds oihana\core\array\toArray
- Adds oihana\core\date\isDate
- Adds oihana\core\date\isValidTimezone
- Adds oihana\core\maths\ceilValue
- Adds oihana\core\maths\floorValue
- Adds oihana\core\maths\roundValue
- Adds oihana\core\numbers\clip
- Adds oihana\core\objects\compress
- Adds oihana\core\strings\fastFormat
- Adds oihana\core\strings\formatRequestArgs
- Adds oihana\core\strings\latinize
- Adds oihana\core\strings\luhn
- Adds oihana\core\strings\randomKey
- Adds oihana\core\strings\toCamelCase
- Adds oihana\core\strings\urlencode
- Adds oihana\core\enums\Boolean
- Adds oihana\core\enums\Char
- Adds oihana\core\exceptions\ExceptionTrait
- Adds oihana\core\exceptions\FileException
- Adds oihana\core\exceptions\ResponseException
- Adds oihana\core\exceptions\UnsupportedOperationException
- Adds oihana\core\exceptions\ValidationException
- Adds oihana\core\exceptions\http\Error404
- Adds oihana\core\exceptions\http\Error500
- Adds oihana\core\exceptions\Reflection
- Adds oihana\core\exceptions\traits\ConstantTrait
- Adds oihana\core\exceptions\traits\ReflectionTrait
- Adds oihana\core\traits\KeyValueTrait
- Adds oihana\core\traits\ToStringTrait
- Adds oihana\core\traits\UnsupportedTrait
- Adds oihana\core\traits\UriTrait
