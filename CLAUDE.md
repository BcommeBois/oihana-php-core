# CLAUDE.md

Guidance for working in the `oihana/php-core` codebase.

## Conventions

- Code, documentation and commit messages are written in **English**.
- New functions are autoloaded via the `autoload.files` list in `composer.json`, carry a full PHPDoc block, and ship with a test under the `tests\` namespace and a `CHANGELOG.md` entry.
- The test suite must stay green **without Xdebug** (the CI `tests` job runs with `coverage: none`, i.e. no Xdebug). Do not rely on Xdebug's `max_nesting_level` to turn runaway recursion into a catchable error — guard against it explicitly.

## Namespaced functions that shadow PHP builtins

PHP resolves an **unqualified function call** by first looking in the current namespace and only falling back to the global builtin if nothing is found — and **function names are case-insensitive**. This library defines several helpers whose names collide (case-insensitively) with a PHP builtin **within their own namespace**:

| Helper | Shadowed builtin |
|---|---|
| `oihana\core\strings\ucWords` | `\ucwords` |
| `oihana\core\strings\ucFirst` | `\ucfirst` |
| `oihana\core\strings\urlencode` | `\urlencode` |
| `oihana\core\strings\key` | `\key` |
| `oihana\core\arrays\shuffle` | `\shuffle` |
| `oihana\core\env\phpVersion` | `\phpversion` |

Consequences and rules:

- **Inside one of those namespaces, an unqualified call to the builtin name hits the maison helper, not the native builtin.** This silently broke `camel()`, where `ucwords(...)` resolved to `ucWords()`. When you need the native PHP function in such a namespace, prefix it: `\ucwords(...)`, `\shuffle(...)`, etc. (or `use function ucwords;` at the top of the file). Prefer the `\` prefix — it is explicit and local to the call.
- **To use a maison function from another namespace**, import it with `use function oihana\core\strings\camel;` (note the `function` keyword) or fully-qualify it: `\oihana\core\strings\camel(...)`.
- **Classes do NOT follow the function fallback rule.** An unqualified class name is looked up *only* in the current namespace — there is no fallback to the global `\`. A class always needs a `use Some\Class;` import (no `function` keyword) or a fully-qualified name (`\DateTime`). This is why `cbor_encode.php` carries both `use Beau\CborPHP\CborEncoder;` (class) and `use function oihana\core\objects\toAssociativeArray;` (function).

Each shadowing helper carries an inline comment flagging the collision.
