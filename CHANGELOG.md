# Oihana PHP Core library - Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Added
- **Objects**
  - Add the `oihana\core\objects\freeze()` function : builds a plain associative array snapshot of the chosen properties of an object. Useful to freeze a reference to another document — copying the selected properties onto the current one so the snapshot survives later changes to the source. Unlike `pick()` + `compress()`, it follows the order of the requested `$fields` (not the declaration order of the object) and reads through `$object->{ $field } ?? null`, so magic `__get()` / `__isset()` accessors are honoured ; missing, uninitialized and inaccessible properties read as `null`. The source object is never modified.
    - **Renaming** : an entry of `$fields` carrying a string key uses that key as the property name in the snapshot, so `[ '_key' , 'thingName' => 'name' ]` lands the source `name` as `thingName`.
    - **Filtering** : the collected values are handed to `arrays\clean()` with the `$flags` bitmask, so the whole `CleanFlag` vocabulary applies. The default, `CleanFlag::NULLS`, only discards `null` — `0`, `0.0`, `''`, `false` and `[]` are kept. `CleanFlag::RETURN_NULL` is rejected with an `InvalidArgumentException`, the function always returning an array.
    - **Depth** : `$deep = true` converts every object or array value into a plain associative array with `toAssociativeArray()`, so the snapshot no longer shares any instance with the source. Left to `false` by default, where object values are copied by handle.

### Changed
- **Static analysis**
  - **The library is now clean at PHPStan level `max`, with zero frozen findings** : the last 20 entries of `phpstan-baseline.neon` — `core/maths` (9), `core/objects` (7) and four scattered singles in `core/date`, `core/helpers`, `core/isLiteral.php` and `core/reflections` — are resolved. `phpstan-baseline.neon` goes from 20 entries to `ignoreErrors: []`. This closes the effort begun in `f7276e6` (246 findings frozen when static analysis was introduced) : eight lots since — `core/accessors`, `core/strings`, `core/arrays`/`core/options`, `core/accessors` again, `core/env`, `core/callables`, `core/documents`, and this one — each cleared its namespace without widening a type, adding a suppression, or weakening the check. The suite stays green (1686 tests) and coverage stays at 100%.
  - `ceilValue()`, `floorValue()` and `roundValue()` narrow their return type from `int|float` to `float` : `ceil()`, `floor()` and `round()` on a `float` argument always return a `float` in PHP, `$value * $r` promoting an `int` input to `float` before either reaches the native function. The `int` branch of the union was unreachable ; behaviour and every documented example are unchanged.
  - `polarToCartesian()`'s `$vector` shape becomes `array{angle?: float, radius?: float}` — both keys optional, matching the second and third documented examples, where a missing key defaults to `0` (or throws, with `$throwable`) rather than being required as the previous `array{angle: float, radius: float}` claimed.
  - `objects\set()` : the `$ref = &$object` / `$ref = &$ref->$segment` reference walk is dropped in favour of a plain, non-reference walk. Object properties mutate through their handle regardless of variable aliasing — unlike `arrays\set()`'s array-backed walk, which genuinely needs by-reference indirection since PHP arrays are value types. The reference indirection here was doing nothing a plain assignment wouldn't, while making the loop's type opaque to static analysis.
  - `objects\toAssociativeArray()` : `$document`/`$target`/`$source` documented as `array<array-key, mixed>` rather than `array<string, mixed>`, matching every other correction of this kind across the library.
  - `helpers\conditions()` : `$conditions`'s array form is documented as `array<int, mixed>` rather than `array<int, callable>`. The narrower type described the *intended* contract, not what the function actually has to handle — the whole point of its `is_array()` branch is to validate entries a caller supplied without static enforcement (PHP does not check array value types) and reject or drop the ones that aren't callable, a path the test suite exercises directly. The validation loop and the final `array_filter()` are split so each keeps a type static analysis can follow, without changing the order-dependent throw-on-first-invalid-entry behaviour.
- **Documents / Static analysis**
  - **`core/documents` is now clean at PHPStan level `max`** : the 12 findings of the namespace are resolved. `phpstan-baseline.neon` drops from 32 to 20 entries. The suite stays green and coverage stays at 100%.
  - `formatDocument()`, `formatDocumentWith()` and `resolvePlaceholders()` walked `foreach ( $doc as ... )` over a value typed `array|object` — legal PHP, since `foreach` accepts a `Traversable` object too, but the three only ever hand it a plain array or a non-iterating object (a `stdClass` snapshot, a data object without `Iterator`/`IteratorAggregate`), which `foreach` also accepts by iterating public properties. Static analysis cannot know that without help : the loops now branch on `is_array( $doc )` and use `get_object_vars( $doc )` on the object side, which is what `foreach` was doing under the hood. `resolvePlaceholders()`, which additionally needs to write each entry by reference to mutate `$target` in place, walks the key list and reads `$doc[$key]` / `$doc->{$key}` by reference — the two are equivalent to the previous `foreach ( $doc as $key => &$value )`, split apart because a single loop cannot bind a by-reference value to both array and object access without losing static typing on one side.
  - `formatDocument()` and `formatDocumentWith()`'s `else if ( is_object( $doc ) )` branch, and its accompanying `else` fallback for "$doc is neither array nor object" — unreachable, `$doc` being natively typed `array|object` — are removed along with the `@codeCoverageIgnore` the fallback carried. The `else if` becomes a plain `else`, `is_array( $doc )` having already been tested.
  - `$document`/`$target`/`$source`, in `strings\format()`, `strings\formatFromDocument()` and the three `core/documents` functions, are documented as `array<array-key, mixed>|object` rather than `array<string, mixed>|object` — a placeholder name is a key path, and a numeric path segment lands as an integer key, the same correction already applied to `core/accessors`.
- **Callables / Static analysis**
  - **`core/callables` is now clean at PHPStan level `max`** : the 16 findings of the namespace are resolved, and one more falls in `core/objects`. `phpstan-baseline.neon` drops from 49 to 32 entries. The suite stays green and coverage stays at 100%.
  - `resolveCallable()` : the native type of `$callable` becomes `mixed`. PHP forbids `callable` inside a union type, so `string|array|object|null` could never spell out what the resolver actually accepts — and any caller holding a value typed `callable` (the normalized `conditions` of `compress()`, for one) was rejected at the boundary. The function is a resolver : it takes a candidate of any shape and answers `null` when it cannot resolve it, which its body already did for every unsupported type. The `@param` lists the accepted forms, `callable` included, and the return type is unchanged.
  - `countCallableParam()` : the memoization cache declares its type — the `static $x = []` variant of the trap corrected in `core/env` — and the `count( $resolved ) === 2` guard is dropped, `resolveCallable()` returning nothing else on that branch.
  - `chainCallables()` : the resolution loop reads back its own last entry (`$resolved[ count( $resolved ) - 1 ] === null`) to detect a failure. It now tests the value it just resolved.
  - `objects\compress()` : the `is_iterable( $conditions )` guard is dropped, as already done in `arrays\compress()` — `CompressOption::normalize()` always yields an array.
- **Env / Static analysis**
  - **`core/env` is now clean at PHPStan level `max`** : the 16 findings of the namespace are resolved and `phpstan-baseline.neon` drops from 65 to 49 entries. The suite stays green and coverage stays at 100%.
  - Thirteen of the sixteen were a single idiom : the `static $x = null ;` memoization every detector uses (`isCli()`, `isLinux()`, `isMac()`, `isWindows()`, `isWeb()`, `isCron()`, `isDocker()`, `isDebug()`, `isInteractive()`, `isColorTerminal()`, `isCliWithFile()`, `cpuCount()`, `phpVersion()`). PHPDoc **does not infer the type of a static variable** — the memo reads as `mixed`, and every one of these functions was returning `mixed` from a `: bool` / `: int` / `: string` signature as far as static analysis was concerned. Each memo now carries a `@var` declaring its type. Same cause as the `static $pairs` maps corrected in `core/strings`.
- **Accessors / Static analysis**
  - **`core/accessors` is now clean at PHPStan level `max`** : the 26 findings of the namespace are resolved and `phpstan-baseline.neon` drops from 91 to 65 entries (88 → 64 blocks). The suite stays green and coverage stays at 100%.
  - **The leaf access of a key path is now decided by the node itself, not by the `$isArray` flag** — see the `Fixed` entry below. `getKeyValue()`, `hasKeyValue()`, `setKeyValue()` and `deleteKeyValue()` test `is_array( $parent )` / `is_array( $document )` where they tested `$isArray`. The two are equivalent for a homogeneous document, `assertDocumentKeyValid()` guaranteeing the invariant, and only differ on the mixed structures `resolveReferencePath()` supports. `deleteKeyValue()` already used `is_array( $parent )` on its own leaf : the idiom is simply generalised.
  - `$separator` is documented as `non-empty-string` throughout the key-path chain — `deleteKeyValue()`, `ensureKeyValue()`, `getKeyValue()`, `hasKeyValue()`, `setKeyValue()`, then `strings\format()`, `strings\formatFromDocument()`, `documents\formatDocument()`, `documents\formatDocumentWith()` and `documents\resolvePlaceholders()`, which all forward it. `assertDocumentKeyValid()` keeps a plain `string` : it is the function that rejects an empty separator, and that runtime check is what makes the annotation true everywhere else.
  - `assertDocumentKeyValid()` gains a `@param-out bool $isArray` : the by-ref argument may be given as `null` but is never written back as such.
  - `deleteKeyValue()` : `$key` is documented as `string|array<int, mixed>` rather than `array<int, string>`. The function validates the list itself and rejects a non-string entry with `All keys must be strings.` — a check the narrower annotation declared unreachable, though the test suite covers it.
  - `arrays\setArrayValue()` : `$document` and the returned array documented as `array<array-key, mixed>`, following the correction already applied to the accessors.
- **Arrays / Options / Static analysis**
  - **`core/arrays` and `core/options` are now clean at PHPStan level `max`** : the 35 findings of `core/arrays` are resolved, and the option shapes introduced below also clear `core/options` entirely and 4 of the 12 entries of `core/objects`. `phpstan-baseline.neon` drops from 132 to 91 entries (122 → 88 blocks). The suite stays green and coverage stays at 100%.
  - **The three option normalizers now declare array shapes** instead of `array<string, mixed>` : `CompressOption::normalize()`, `ArrayOption::normalize()` and `MergeOption::normalize()` document both the partial array they accept and the complete array they return. The consumers follow suit — `arrays\compress()`, `objects\compress()`, `merge()`, `prepare()` and `reduce()` declare the same shapes, so an option name typo or a wrongly-typed option value is now a static error at the call site instead of a `mixed` travelling to the bottom of the call chain. `objects\compress()` also gains the `clone` key in its shape : it never reads it, but `arrays\compress()` hands it the whole normalized array on the recursive path.
  - `helpers\conditions()` : `@return` narrowed from `array<callable>` to `array<int, callable>`, which is what `array_filter()` produces there.
  - `arrays\compress()` : the `is_iterable( $conditions )` guard is dropped. `CompressOption::normalize()` always runs first and always yields an array, so the test could never be false. Likewise in `prepare()`, the `?? []` and `?? true` fallbacks on `firstKeys` and `sort` are removed — the normalizer fills both.
  - `reorder()` and `arrays\set()` : `$array` and the returned array are documented as `array<array-key, mixed>` rather than `array<string, mixed>`, the same correction already applied to `core/accessors` — a numeric path segment lands as an integer key, and `reorder()` sorts integer keys just as well.
  - `unique()` : values are documented as `scalar`. The default `SORT_STRING` (like `SORT_NUMERIC` and `SORT_LOCALE_STRING`) requires values comparable as strings ; only `SORT_REGULAR` tolerates arrays, and the function never advertised that.
  - `groupBy()` and `keyBy()` : `$keyer` is documented as `callable(mixed, int|string): (int|string|float|bool|null)`. The prose announced `int|string` only, while both functions have always cast anything else — a behaviour the test suite exercises.
- **Strings / Static analysis**
  - **`core/strings` is now clean at PHPStan level `max`** : the 111 findings the namespace carried are resolved and `phpstan-baseline.neon` drops from 243 to 132 entries (216 → 122 blocks). No entry of the namespace is left frozen. The suite stays green and coverage stays at 100%.
  - **Rendering a `mixed` value as a string now goes through `toString()`** rather than a bare `(string)` cast, in `between()`, `formatFromDocument()`, `formatRequestArgs()`, `keyValue()`, `predicate()`, `resolveList()` and `uniqueKey()`. Scalars are unaffected. An array value used to produce PHP's `"Array"` plus an `Array to string conversion` warning (or, on the fast path of `formatFromDocument()`, a `TypeError`) and now renders as its comma-joined flattening — `[ 1 , 2 ]` becomes `1,2`. `fastFormat()` follows the same intent with its own vocabulary : a non-scalar argument is described (`[array]`) instead of being cast, consistent with the `[object Foo]` it already emitted for a non-`Stringable` object.
  - `toString()` : the branch rendering `-0.0` as `'-0'` is removed. It was dead — `sprintf( '%.1f' , -0.0 )` yields `'0.0'` on the platforms tested, and since PHP 8.0 the plain `(string)` cast already produces `'-0'` — and it carried a `@codeCoverageIgnore`. The documented behaviour is unchanged. A value with no string form (non-`Stringable` object, resource) now yields `''` where a non-`Stringable` object used to raise an `Error` ; the PHPDoc states it.
  - `compile()` : the `@param` of `$expressions` announced `string|Stringable|array|null` where the native type is `mixed` and the body handles booleans, arbitrary objects and "unsupported types" — three branches the annotation declared unreachable. It now reads `mixed`, matching both the signature and the prose. A resource yields `''` (as the prose already promised) instead of `Resource id #n`.
  - `keyValue()` : `$key` accepts `Stringable` in place of `object`. Since PHP 8.0 any class declaring `__toString()` implements `Stringable` implicitly, so every key that used to work still does — an object without `__toString()`, which raised an `Error` inside the function, is now rejected at the boundary.
  - `snake()` : the six sequential `preg_replace()` calls are batched into a single call over a pattern/replacement map (same patterns, same order, same result), and the cache is read with one `SnakeCache::get()` instead of `has()` + `get()`.
  - `object()` : the unreachable `default` arm of the `match` — throwing on a `$keyValues` outside `null|string|array`, which the native signature already forbids — is removed along with its `@codeCoverageIgnore`.
  - `split()` : the `@return` now documents that a segment is a `[ segment , offset ]` pair when `PREG_SPLIT_OFFSET_CAPTURE` is passed, instead of promising `array<int, string>` unconditionally. `sanitize()`'s `$options` and `toPhpString()`'s internal `$options` are documented as array shapes rather than `array<string, mixed>`, and `resolveList()`'s `$separator` as a `non-empty-string` (an empty separator has always raised a `ValueError` from `explode()`).

### Fixed
- **Date**
  - `formatDateTime( null , 'UTC' , null )` emitted `Deprecated: DateTimeImmutable::format(): Passing null to parameter #1 ($format) of type string is deprecated` — `$format` is documented and typed as nullable, but was forwarded straight to `DateTimeImmutable::format()`, which requires a `string`. A `null` `$format` now falls back to the function's own default pattern, `'Y-m-d\TH:i:s.v\Z'`, silently.
- **Objects**
  - `toAssociativeArray( $object , strict: true )` returned whatever `JsonSerializable::jsonSerialize()` handed back — unchecked — into `array_map()`, which raised a `TypeError` on anything but an array. It now raises an `InvalidArgumentException` naming the actual contract violation.
  - `toAssociativeArray()` with a non-`null` `$encoder` fed the encoder's return value straight to `json_decode()`, which requires a `string` ; an encoder returning anything else raised a `TypeError` deep inside `json_decode()` rather than at the boundary it crossed. It now raises an `InvalidArgumentException` naming the encoder as the source of the mismatch. A `json_decode()` failure (malformed JSON) still returns `null` from `json_decode()`, now normalized to `[]` to keep the function's `: array` promise instead of failing the return-type check.
- **Accessors**
  - Documentation of the `$document` parameter (and of the returned document) of `assertDocumentKeyValid()`, `deleteKeyValue()`, `ensureKeyValue()`, `getKeyValue()`, `hasKeyValue()`, `resolveReferencePath()` and `setKeyValue()`, annotated as `array<string, mixed>|object` where the functions accept — and have always handled — any array key. A path segment is a string, but PHP casts a numeric string offset to an integer, so `getKeyValue( [ 'a' , 'b' ] , '1' )` returns `'b'` and `getKeyValue( [ 10 => [ 20 => 'x' ] ] , '10.20' )` returns `'x'` ; the annotation wrongly reported those calls as type errors. Now `array<array-key, mixed>|object`. The seven matching `phpstan-baseline.neon` entries are updated to the new message text (the baseline keeps its 216 entries). No behaviour change.
- **Callables**
  - `getCallableType()` wrote non-callables into its `$norm` by-ref argument, which is declared `callable|null`. On failure it handed back the value as given — `getCallableType( 123 , false , $norm )` left `$norm` at `123` — and on the `[ $target , $method ]` branches it stored the pair without checking it was callable at all, so a private or protected method left a pair that could not be invoked. `$norm` now only ever carries a usable callable, and is `null` otherwise. The array branch also type-checks `$target` and `$method` before handing them to `method_exists()` and `ReflectionMethod`, where a malformed pair such as `[ 1 , 2 ]` raised a `TypeError`. One test asserted the old leak (`assertSame( 123 , $norm )`) and now asserts `null`.
- **Env**
  - `cpuCount()` fed the result of `file_get_contents( '/proc/cpuinfo' )` straight to `substr_count()`. The read can fail after the `file_exists()` check — a permission change, a race — and `false` would have raised a `TypeError` on the Linux path. A failed read now falls back to `1`, like the branch for every other platform.
  - `isCliWithFile()` guarded `$_SERVER['argv'][0]` with `isset()` only, which says nothing about the type. A non-string `argv[0]` — `$_SERVER` being writable by the host — would have raised a `TypeError` from `file_exists()`. The entry is now type-checked before use.
- **Accessors**
  - `getKeyValue()`, `hasKeyValue()` and `setKeyValue()` raised an `Error` on a **mixed structure** — an array holding an object, or the reverse — which is precisely the scenario `resolveReferencePath()` advertises as supported in its own PHPDoc. Reaching the leaf, they picked the access mode from the `$isArray` flag, which describes the *root* document, where `resolveReferencePath()` returns whatever node the path actually leads to. `setKeyValue( [ 'data' => (object) [ 'name' => 'Alice' ] ] , 'data.age' , 30 )` failed with `Cannot use object of type stdClass as array`, and `hasKeyValue()` with a `TypeError` from `array_key_exists()`. The three now inspect the node they reached. `deleteKeyValue()` was already correct on that path but shared the flaw on its wildcard branch, now aligned.
  - `ensureKeyValue()` raised a `TypeError` from `assertDocumentKeyValid()` when the `$keys` list held a non-string entry given by position (`ensureKeyValue( $doc , [ 42 ] )`). The case is now rejected with the `InvalidArgumentException` the PHPDoc announces, carrying the same `All keys must be strings.` message as `deleteKeyValue()`.
  - `hasKeyValue()` no longer hands a non-object node to `property_exists()` : a scalar reached along the path answers `false` instead of raising a `TypeError`.
- **Arrays**
  - `reduce()` handed `( $key , $value )` to a user-supplied callable where its own PHPDoc announces `fn( $value , $key ): bool`. Every other predicate of the library — the `conditions` of `compress()`, and PHP's own `ARRAY_FILTER_USE_BOTH` — passes the value first, and the PHPDoc example (`reduce( $data , fn( $v , $k ) => is_string( $v ) && $v !== '' )`) returned the whole array instead of the filtered one. The arguments are now passed in the documented order. Two tests had been written around the inverted order — naming the first parameter `$k` while asserting the documented result — and are corrected ; their assertions are unchanged.
  - `delete()` raised a `TypeError` when `$key` was an empty array : `array_shift( [] )` yields `null`, which `array_key_exists()` refuses as a key. An empty key list now returns the target untouched.
  - `set()` with a null `$key` and a non-array `$value` assigned the value to the referenced array — corrupting the caller's variable — before failing on the `: array` return type. The case is now rejected with an `InvalidArgumentException` before anything is written, and the PHPDoc states that `$value` must be an array when `$key` is null.
- **Strings**
  - `ucWords()` crashed on a malformed UTF-8 input : the `/u` pattern makes `preg_replace_callback()` return `null`, which a `: string` function cannot return, so `ucWords( "he\xFFllo" )` raised a `TypeError`. Such a subject is now returned untouched.
  - `snake()` on a malformed UTF-8 input emitted a cascade of `preg_replace(): Passing null to parameter #3 ($subject) is deprecated` notices — the first `/u` pattern returned `null` and every following step was fed that `null` — before landing on `''`. It now returns `''` directly, without the notices.
  - `formatFromDocument()` : the fast path taken when the template is exactly one placeholder returned the raw document value, escaping the `: string` return contract — an array value raised a `TypeError` where the multi-placeholder path rendered it. Both paths now share the same conversion.
  - `convertObject()` (`toPhpString()`) read `$options['maxDepth']`, `$options['quote']` and `$options['compact']` without a default, so calling it directly with the partial options array its own PHPDoc example shows raised `Undefined array key` warnings. It now falls back to the same defaults as `convert()`.
  - `pad()` with `STR_PAD_BOTH` derived its side lengths from `ceil()`, i.e. floats, and handed them to `grapheme_substr()`. The lengths are now integers.
  - `luhn()` doubled the digits as strings (`$number[$i] * 2` on a one-character string) ; the digit is now read as an `int`. Same results, no implicit coercion.
  - `replacePathPlaceholders()` : in `$path == null || $path == ''` the second test was unreachable, `'' == null` already being true. Both tests are now strict and each carries its own case.
  - Documentation of `toPhpString()`, whose example 4 passed `'indent' => 2` and displayed a two-space indentation. `indent` is used as-is by `str_repeat()`, so an integer renders a literal `"2"` at each level ; the example now passes `'  '`.
- **Arrays**
  - `clean()` no longer injects a `null` into its own output. `CleanFlag::RETURN_NULL` was propagated to the recursive call, so a nested array that cleaned to empty came back as `null` and was stored as such — a `null` that `CleanFlag::NULLS` could not catch, the value having already gone down the array branch. The flag is now stripped before recursing : it describes the contract of the outermost call only. Trigger window was `RETURN_NULL | RECURSIVE` **without** `EMPTY_ARR`, hence `CleanFlag::DEFAULT` and `CleanFlag::NORMALIZE` were never affected.
  - `clean()` now honours `CleanFlag::RETURN_NULL` on an already empty input, which was the last "nothing left" case ignoring it : `clean( [] , CleanFlag::NORMALIZE )` yielded `[]` where `clean( [ 'a' => null ] , CleanFlag::NORMALIZE )` yielded `null`, and the validation pattern the PHPDoc recommends (`if ( $cleaned === null )`) accepted a wholly absent input while rejecting a blank one. The early return that shadowed the flag test is removed rather than taught about it, so the return contract keeps a single decision point ; flag validation still runs first. `normalize()` is unaffected, having always compensated on its own side.
  - Documentation of `CleanFlag::TRIM`, which announced that it trims strings and implies `CleanFlag::EMPTY`. In `clean()` it does neither : it is a modifier of `EMPTY` — widening the drop test from `$value !== ''` to `trim( $value ) !== ''` — so it has no effect on its own, and the values it keeps are never altered (`'  x  '` stays `'  x  '`). The constant now documents both readings, `normalize()` reading the same flag and actually trimming there. No behaviour change.
  - Documentation of `CleanFlag::FALSY`, which announced that it removes `[]`. It only ever applies to the scalar branch of `clean()`, so empty arrays survive it and `CleanFlag::EMPTY_ARR` is required to discard them ; example 5 of the `clean()` PHPDoc announced `['ok']` where the function returns `['ok', []]`. No behaviour change.
- **Static analysis**
  - Regenerate `phpstan-baseline.neon` from a cold result cache. The committed baseline had been generated with a warm one and did not survive `phpstan clear-result-cache`, so a fresh checkout — as on CI — reported errors on an otherwise green tree. Two stale `identical.alwaysFalse` entries in `arrays/compress.php` and `objects/compress.php` are gone, and an `offsetAccess.nonOffsetAccessible` count on `callables/countCallableParam.php` is corrected from 1 to 2.

## [1.1.0] - 2026-07-26

### Added
- **Container**
  - Add the `oihana\core\container\resolveDependency()` function : resolves a string id from a PSR-11 container (returns `$container->get($id)` when present) or returns a default otherwise — safe with a `null` container, a `null` or empty id, and a missing entry. New `psr/container` (`^2.0`) dependency. Relocated from `oihana\controllers\helpers` in `oihana/php-system` (a generic PSR-11 resolver with no HTTP/controllers link) so low-level libraries can depend on it without pulling the controllers layer.
- **Date**
  - Add the `durationToSeconds()` function : normalizes a duration — an int/float of seconds, a `"MM:SS"`/`"HH:MM:SS"` colon string, a `"1.5d 3h 15m 12.5s"` unit string (any subset, any order, decimals allowed), or `null` — into a number of seconds. A day is worth `$hoursPerDay` hours (default 24).
  - Add the `humanizeDuration()` function : renders any of the above duration forms as a human-readable string (e.g. `"1h 2m 5s"`). Built on `durationToSeconds()` so every input is first reduced to seconds and then broken down, which makes overflow roll up consistently (`"90:00"` and `"1.5h"` both yield `"1h 30m"`) ; only the seconds component may carry a fractional part.
  - Add the `DurationUnit` class : `DurationUnit::DAY` / `HOUR` / `MINUTE` / `SECOND` constants (plus `all()` / `isValid()`) for the `'d'` / `'h'` / `'m'` / `'s'` duration suffixes parsed by `durationToSeconds()` and emitted by `humanizeDuration()`, avoiding magic strings.
- **Interfaces**
  - Add the `Invalidable` interface : contract for objects holding a derived state that can become stale, exposing a single `invalidate() : void` method. It lets a producer of change tell its dependents to forget what they derived, without knowing what — or how — they cache it.
- **Maths**
  - Add the `aspectFit()` function : scales a width/height pair to a target width or height while preserving the original aspect ratio (returns `array{width:int,height:int}`). `targetWidth` takes precedence over `targetHeight` ; a non-positive original dimension returns the provided targets (or the originals) unchanged.
  - Add the `Dimension` class : `Dimension::WIDTH` / `Dimension::HEIGHT` constants (plus `all()` / `isValid()`) for the canonical keys of a size pair, avoiding the `'width'` / `'height'` magic strings used by `aspectFit()`.
- **Numbers**
  - Add the `modf()` function : splits a number into its integral and fractional parts like the C `modf()` (truncation toward zero, so the sign is preserved on both parts ; `INF` yields a `0.0` fraction and `NAN` propagates to both parts).

### Changed
- **Types / Static analysis**
  - Begin annotating array shapes for PHPStan (level max) — `@param`/`@return` value types on `array` so static analysis can reason about contents. No runtime behaviour changes; the suite stays green and coverage stays at 100%.
    - `interfaces` : `Arrayable::toArray()` (`array<int|string, mixed>`), `ClearableArrayable::toArray()` and `ToAssociativeArray::toArray()` / its `$options` parameter (`array<string, mixed>`).
    - `core/accessors` : document shapes on `assertDocumentKeyValid()`, `deleteKeyValue()`, `ensureKeyValue()`, `getKeyValue()`, `hasKeyValue()`, `resolveReferencePath()` and `setKeyValue()` — `$document` and returns as `array<string, mixed>|object`, key-path lists as `array<int, string>` (`ensureKeyValue()`'s `$keys` map as `array<int|string, mixed>`).
    - `core/objects` : property-name lists (`hasAllProperties()`, `hasAnyProperty()`, `omit()`, `pick()`) as `array<int, string>` ; `keys()`/`values()` returns as `list<string>`/`list<mixed>` ; `set()`'s `$classFactory` map as `array<string, class-string>` ; `toAssociativeArray()` document/encoder/return shapes.
    - `core/strings` : line/separator lists (`block()`, `blockPrefix()`, `blockSuffix()`, `wrapBlock()`, `camel()`, `pascal()`, `key()`, `predicates()`) as `array<int, string>` ; document/options maps (`format()`, `formatFromDocument()`, `formatRequestArgs()`, `sanitize()`, `uniqueKey()`, `toPhpString()`'s internal `convert()`/`convertObject()`/`convertArray()`) as `array<string, mixed>` (circular-ref caches as `array<string, bool>`) ; `compile()`/`object()`/`resolveList()` mixed inputs as `array<int|string, mixed>` ; `split()` return as `array<int, string>` ; `replacePathPlaceholders()`'s `$args` as `array<string, scalar|\Stringable>` ; `SnakeCache::$cache` as `array<string, array<string, string>>`.
    - `core/strings/urlencode()` : narrow the native return type from `array|string` to `string` (the function only ever returns a string — `str_replace()` over a string subject), matching the already-documented `@return string`. Behaviour unchanged.
    - `core/maths` : `mean()`, `median()`, `stddev()`, `variance()` value lists as `array<int, int|float>` (also clears the `median()`/`variance()` "binary op on mixed" errors).
    - `core/callables` : callable candidates as `string|array<int, object|string>|object` (`chainCallables()`, `countCallableParam()`, `resolveCallable()`).
    - `core/documents` : document shapes on `formatDocument()`, `formatDocumentWith()`, `resolvePlaceholders()` (`$source`/`$target`/returns as `array<string, mixed>|object`).
    - `core/options` : `ArrayOption`/`CompressOption`/`MergeOption` `normalize()` params and returns as `array<string, mixed>`.
    - `core/helpers/conditions()` : `$conditions` array form as `array<int, callable>`.
    - `core/reflections/getFunctionInfo()` : precise `@return` array shape (`array{name: string, namespace: string, alias: string, file: string|false, startLine: int|false, endLine: int|false, isInternal: bool, isUser: bool, comment: string|null}|null`).
    - `core/arrays` (part 1, `append()` → `keyBy()`) : generic array inputs/returns as `array<int|string, mixed>` ; associative-only inputs (`get()`) as `array<string, mixed>` ; key-path / key-list params as `array<int, string>` / `array<int, int|string>` ; `flatten()` return as `array<int, mixed>` ; `groupBy()` return as `array<int|string, array<int|string, mixed>>` ; `exists()` accepts `ArrayAccess<int|string, mixed>` ; `isCallableWithParams()` `$array` as `array<int, mixed>`.
    - `core/arrays` (part 2, `merge()` → `unique()`) : generic array inputs/returns as `array<int|string, mixed>` ; associative-only inputs (`reorder()`, `set()`, `setArrayValue()`) as `array<string, mixed>` ; option maps (`merge()`, `prepare()`, `reduce()`) as `array<string, mixed>` ; key lists (`omit()`, `pick()`, `removeKeys()`) as `array<int, int|string>` ; `partition()` return as `array{0: array<int|string, mixed>, 1: array<int|string, mixed>}` ; `unique()` return as `array<int, mixed>`.
  - With part 2 done, **every `missingType.iterableValue` error reported by PHPStan at level `max` is now resolved** (152 cleared across `interfaces` and all `core/*` namespaces). No runtime behaviour changed; the suite (1639 tests) stays green and coverage stays at 100%.
- **Tooling**
  - Add [PHPStan](https://phpstan.org/) (`phpstan/phpstan` `^2.2`) as a dev dependency with a `phpstan.neon` configured at level `max` over `src`. The 246 findings predating static analysis are frozen in `phpstan-baseline.neon` so `composer phpstan` is green and only reports new issues; shrink the baseline over time and regenerate it with `composer phpstan:baseline`. Documented under a new "Static Analysis (PHPStan)" section in the README.
  - Bump the `phpunit/phpunit` dev dependency from `^12` to `^13` and update the `phpunit.xml` schema reference to `13.2`. The full suite (1639 tests) stays green. PHPUnit 13 requires PHP ≥ 8.4, already the project's minimum.

### Fixed
- **Strings**
  - `blockPrefix()` : remove the unreachable `? ''` ternary branch in the per-line mapper. When `$keepEmptyLines` is `false`, empty lines are already stripped upstream (`PREG_SPLIT_NO_EMPTY` for a string input, `array_filter()` for an array input), so the `$line === '' && !$keepEmptyLines` guard could never be true. Behaviour is unchanged. This also drops the now-useless `@codeCoverageIgnore` annotation and restores 100% line coverage : `phpunit/php-code-coverage` 14 (pulled in by PHPUnit 13) attributes the arrow-function statement to a line just outside the ignored block, which had lowered coverage to 99.95%.

## [1.0.10] - 2026-06-08

### Fixed
- **CBOR**
  - `cbor_encode()` no longer recurses indefinitely on values the encoder cannot handle (e.g. a resource). The underlying encoder left such values to its identity replacer and recursed forever, which only Xdebug's nesting limit turned into a catchable error ; without Xdebug (as in CI) the process exhausted memory and was killed. A default replacer now rejects unencodable values so the failure deterministically surfaces as a `500` `RuntimeException`.
- **Strings**
  - `camel()` is now multibyte-safe and no longer mis-capitalizes after non-separator characters. Because PHP function names are case-insensitive, the unqualified `ucwords()` call resolved to the `ucWords()` helper added in 1.0.9 (which upper-cases after any non-alphanumeric char), so `camel('hello@world')` returned `'hello@World'`. It now builds on the multibyte `ucFirst()` applied per whitespace-split word, so only declared separators act as word boundaries and accented words convert correctly (e.g. `'éléphant_école'` -> `'éléphantÉcole'`).

## [1.0.9] - 2026-06-07

### Added
- **Arrays**
  - Add the `groupBy()` function : groups items into buckets keyed by a computed value (original keys preserved).
  - Add the `keyBy()` function : indexes items by a computed key (last one wins on collision).
  - Add the `partition()` function : splits items into a `[ passed , failed ]` pair according to a predicate (keys preserved).
  - Add the `find()` function : returns the first item matching a predicate, or a default value.
  - Add the `firstWhere()` function : readability alias of `find()`.
  - Add the `sortBy()` function : returns a stable copy sorted by a computed value, ascending or descending (keys preserved).
- **Date**
  - Add the `isWeekend()` function : tells whether a date falls on a Saturday or a Sunday.
  - Add the `isPast()` function : tells whether a date is strictly before now (optional reference `$now`).
  - Add the `isFuture()` function : tells whether a date is strictly after now (optional reference `$now`).
  - Add the `addDays()` function : returns a new immutable date shifted by a number of days (negative subtracts ; source untouched).
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
  - Add the `omit()` function : returns a new `stdClass` without the given public properties (inverse of `pick()`).
  - Add the `keys()` function : returns the list of an object's public property names.
  - Add the `values()` function : returns the list of an object's public property values.
  - Add the `map()` function : returns a new `stdClass` with each public property value transformed by `fn($value, $key)`.
  - Add the `filter()` function : returns a new `stdClass` keeping the public properties for which `fn($value, $key)` is truthy.
- **Strings**
  - Add the `slugify()` function : converts a string into a URL-friendly slug (latinize + lower-case + non-alphanumeric → separator).
  - Add the `truncate()` function : grapheme-safe truncation to a maximum length, appending an ellipsis (not counted in the length).
  - Add the `mask()` function : masks the middle of a string, keeping a few grapheme clusters visible at each end (sensitive data).
  - Add the `capitalize()` function : uppercases the first character and lowercases the rest (multibyte-safe).
  - Add the `ucFirst()` function : multibyte-safe uppercasing of the first character (rest untouched).
  - Add the `ucWords()` function : multibyte-safe uppercasing of the first letter of each word.
  - Add the `pascal()` function : converts a string to PascalCase (builds on `camel()` + `ucFirst()`).

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
