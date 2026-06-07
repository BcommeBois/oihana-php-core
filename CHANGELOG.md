# Oihana PHP Core library - Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Added
- **Arrays**
  - Add the `groupBy()` function : groups items into buckets keyed by a computed value (original keys preserved).
  - Add the `keyBy()` function : indexes items by a computed key (last one wins on collision).
  - Add the `partition()` function : splits items into a `[ passed , failed ]` pair according to a predicate (keys preserved).
  - Add the `find()` function : returns the first item matching a predicate, or a default value.
  - Add the `firstWhere()` function : readability alias of `find()`.
  - Add the `sortBy()` function : returns a stable copy sorted by a computed value, ascending or descending (keys preserved).
- **Maths**
  - Add the `mean()` function : arithmetic mean (average) of a list of numbers (throws on an empty array).
  - Add the `median()` function : median of a list of numbers (sorts, then takes the middle value or averages the two central ones ; throws on an empty array).
  - Add the `variance()` function : population variance by default, sample variance (`N - 1`) when requested (throws on empty input, or on sample mode with fewer than two values).
  - Add the `stddev()` function : standard deviation (square root of `variance()`), population or sample (same guards as `variance()`).
  - Add the `factorial()` function : factorial `n!` for `n` in `[0, 20]` (throws on a negative argument or above `20`, where the result would overflow `int`).
  - Add the `isPrime()` function : primality test using `6k ± 1` trial division (any integer below `2` is not prime).
- **Numbers**
  - Add the `clamp()` function : a clarified alias of `clip()` bounding a value between a min and a max (both names kept).
  - Add the `lerp()` function : linear interpolation between two values (unbounded factor, extrapolation allowed).
  - Add the `mapRange()` function : re-maps a value from one range to another (throws `InvalidArgumentException` on a degenerate input range).
  - Add the `sign()` function : returns `-1`, `0` or `1`.
  - Add the `isEven()` function : integer even-parity test.
  - Add the `isOdd()` function : integer odd-parity test.
  - Add the `percentage()` function : computes a part/total percentage, guarding against division by zero (returns `0.0`).
- **Objects**
  - Add the `pick()` function : returns a new `stdClass` keeping only the given public properties (source object untouched).
- **Strings**
  - Add the `slugify()` function : converts a string into a URL-friendly slug (latinize + lower-case + non-alphanumeric → separator).
  - Add the `truncate()` function : grapheme-safe truncation to a maximum length, appending an ellipsis (not counted in the length).
  - Add the `mask()` function : masks the middle of a string, keeping a few grapheme clusters visible at each end (sensitive data).

## [1.0.8] - 2026-06-07

### Added
- **Accessors**
  - Add the `ensureKeyValue` function : Ensures that one or more keys or properties exist in an array or object.
- **Arrays**
  - Add the `append()` and `prepend()` functions 
  - Add the `isCallableWithParams()` function 
  - Add the `omit()` and `pick()` functions
  - Add the `reduce()` function : Reduces an array by removing values based on conditions or using compress/clean.
  - Add the `reorder()` function : Reorders an associative array by placing specified keys first, optionally sorting the rest.
  - Add the `merge()` function with the `MergeOption` and `NullsOption` helpers.
  - Add the `prepare()` function.
- **Bits**
  - Add the `BitFlagTrait` trait : shared `has()`, `isValid()`, `getFlags()` and `describe()` methods (plus the common `NONE = 0` constant) for bitmask flag enumerations such as `CleanFlag` and `SanitizeFlag`.
- **Callables**
  - Add the `countCallableParam` function : Returns the number of parameters of a given callable.
- **CBOR**
  - Add the `cbor_encode()`, `cbor_decode()` functions
- **Encoding**
  - Add the `base64UrlEncode()` and `base64UrlDecode()` functions : URL-safe base64 encoding/decoding (RFC 4648 §5) with strict alphabet validation and tolerant padding on decode.
  - Add the `hexEncode()` and `hexDecode()` functions : lowercase hexadecimal encoding/decoding with strict alphabet and length validation (`false` on invalid input, no PHP warning).
  - Add the `randomBase64Url()` and `randomHex()` functions : cryptographically secure random token generators (CSPRNG, 32 bytes / 256 bits of entropy by default).
- **Strings**
  - Add the `chunk()` function : Splits a string into groups of length, separated by a separator.
  - Add the `parseSteps()` function :  * Parses a step-range expression into a sorted, deduplicated list of integer steps in `[1, $maxStep]`.
  - Add the `replace()` function : Replaces all occurrences of a substring within a string, with optional Unicode (grapheme-safe) and normalization support.
  - Add the `sanitize()` function : Sanitize a string based on configurable flags.
  - Add the `SanitizeFlag` enumeration : the bitmask flags consumed by `sanitize()` (uses `BitFlagTrait`).
  - Add the `split()` function : Splits a string into an array using a regular expression separator.
  - Add the `stripDoubleQuotes()` function : Strips a single layer of surrounding `"…"` double quotes (RFC 7230 `quoted-string` compatible, without decoding quoted-pair escapes).
  - Add the `unquote()` function : Strips a single layer of surrounding matching quote characters (`'`, `"`, `` ` ``, `«…»`, `“…”`, `‘…’`).
  - Add the `isQuoted()` function : Predicate that returns `true` if a string is wrapped in a matching pair of quote characters.
  - Add the `getQuoteChar()` function : Returns the opening quote character used to wrap a string, or `null`.
  - Add the `splitOutsideQuotes()` function : Splits a string by a separator, ignoring separators inside quoted regions; supports backslash escape and multi-byte separators.
  - Add the `parseParameters()` function : Generic `key=value; …` parser built on top of `splitOutsideQuotes()` and `unquote()`; configurable item/key-value separators and optional lowercase keys.
- **Options**
  - Add the ArrayOption helper
- **Interfaces**
  - Add the `ToAssociativeArray` interface : contract for objects exposing a `toArray( array $options = [] )` method.

### Changed
- **Arrays**
  - Fix the removeKeys function to clone by default the passed-in array definition.
  - The `compress()` function accept the conditions with callable function with one or two arguments : `fn( $v , $k )` or `fn( $v )`.
  - Reimplement `isIndexed()` and `isAssociative()` on top of the native `array_is_list()`.
- **Strings**
  - The `key()` function now accepts an array of segments (`null|string|array`), e.g. `key(['a','b'], 'doc')` returns `'doc.a.b'`.
- **Objects**
  - Adds the optional 'encoder' argument in the `toAssociativeArray( array|object $data , string|array|object|null $encoder = null )` function.
  - Adds the optional `strict` argument in `toAssociativeArray( … , bool $strict = false )`.
  - The `compress()` function accept the conditions with callable function with one or two arguments : `fn( $v , $k )` or `fn( $v )`.

### Fixed
- **Reflections**
  - Register `getFunctionInfo()` in the Composer autoload `files` list so the function is actually loadable (it was defined but never autoloaded, hence unusable).
- **Documentation**
  - Correct wrong `@package` tags (`parseSteps`, `cbor_encode`/`cbor_decode`, `isLinux`/`isMac`/`isWindows`, `formatDocument`).
  - Fix `getJsonType()` `@return` documentation (`'number'` instead of `'double'`).
  - Complete missing `@param`, `@return`, `@throws` and `@example` tags across the library.

## [1.0.7] - 2025-12-12

### Added
 - Add the oihana/core/normalize function : Normalizes a value according to the given cleaning flags.
 - Add the oihana/core/toNumber function : Converts a value to a numeric type (int or float) if possible.
 - **Accessors:**
   - Add the `deleteKeyValues()` function + add in the `deleteKeyValue()` function the array|string $key parameter (multiple keys deletion)
 - **Bits:**
   - `countFlags()`, `hasAllFlags()`, `hasFlag()`, `isValidMask()`, `setFlag()`, `toggleFlag()` and `unsetFlag()`
 - **Callables:**
   - Add the `isCallable()` and `resolveCallable()` functions
   - Add the `chainCallables()`, `memoizeCallable()`, `middlewareCallable()` and `wrapCallable()` functions
   - Add the `getCallableType()` function and the `CallableType` constant enumeration.
 - **JSON:**
   - Add the `getJsonType()` function 
 - **Strings:**
   - Add the `uniqueKey()` function
   - Add the `replacePathPlaceholders()` function 
   - Add the `resolveList()` function 

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
