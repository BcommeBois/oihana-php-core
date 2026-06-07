# Oihana PHP Core — Roadmap (new functions)

Planned additions to the library, grouped in independent batches (one batch ≈ one
reviewable commit: functions + unit tests + PHPDoc). Priority order reflects how
thin each namespace currently is and how often the helpers are needed.

> Conventions for every new function:
> - Free function in its own file under `src/oihana/core/<ns>/<name>.php`, namespace `oihana\core\<ns>`.
> - Register the file in `composer.json` `autoload.files` (then `composer dump-autoload`).
> - Full PHPDoc: `@param` / `@return` / `@throws` (if any) / `@example` / `@package` / `@author` / `@since 1.0.8`.
> - Unit tests under `tests/oihana/core/<ns>/<Name>Test.php`, namespace `tests\oihana\core\<ns>`, with `use function ...` imports.
> - Update `CHANGELOG.md` `[Unreleased] > Added`.
> - Avoid duplicating native PHP (noted below where relevant).

---

## Lot 1 — `numbers/` (highest priority — only `clip()` today)
- `clamp(int|float $v, int|float $min, int|float $max)` — alias/clarified name for `clip()` (keep both).
- `lerp(float $a, float $b, float $t)` — linear interpolation.
- `mapRange(float $v, float $inMin, float $inMax, float $outMin, float $outMax)` — re-map a value between ranges.
- `sign(int|float $v): int` — -1 / 0 / 1.
- `isEven(int $v): bool` / `isOdd(int $v): bool`.
- `percentage(int|float $part, int|float $total): float` — guard against division by zero.

## Lot 2 — `arrays/` (collection helpers not covered by native PHP)
- `groupBy(array $items, callable $keyer): array` — group rows by a computed key.
- `keyBy(array $items, callable $keyer): array` — index rows by a computed key.
- `partition(array $items, callable $predicate): array{0:array,1:array}` — split into [pass, fail].
- `find(array $items, callable $predicate): mixed` / `firstWhere` — first matching element (or null).
- `sortBy(array $items, callable $selector, bool $desc = false): array` — stable sort by computed value.
- _(Skip: `pluck` → `array_column`, `chunk` → `array_chunk`, `only/except` → existing `pick/omit`.)_

## Lot 3 — `strings/` (one strong gap + optional helpers)
- `slugify(string $s, string $separator = '-'): string` — `latinize` + lower + non-alnum→separator (URL slugs).
- _(Optional)_ `truncate(string $s, int $length, string $ellipsis = '…'): string` — grapheme-safe.
- _(Optional)_ `mask(string $s, int $visibleStart = 0, int $visibleEnd = 4, string $char = '*'): string` — mask sensitive data.
- _(Skip: `contains/startsWith/endsWith` → native `str_contains/str_starts_with/str_ends_with`.)_

## Lot 4 — `maths/` (statistics, complements gcd/haversine)
- `mean(array $values): float` (a.k.a. `average`), `median(array $values): float`.
- `variance(array $values, bool $sample = false): float`, `stddev(array $values, bool $sample = false): float`.
- `sum(array $values): int|float`, `factorial(int $n): int` (throws on negative), `isPrime(int $n): bool`.

## Lot 5 — `objects/` (symmetry with arrays/accessors)
- `pick(object $o, array $keys): object` / `omit(object $o, array $keys): object`.
- `keys(object $o): array` / `values(object $o): array`.
- `map(object $o, callable $fn): object` / `filter(object $o, callable $fn): object`.

## Lot 6 — `date/` (optional — namespace kept intentionally small for now)
- `isWeekend(DateTimeInterface $d): bool`, `isPast(...)` / `isFuture(...)`, `addDays(DateTimeInterface $d, int $days): DateTimeInterface`.

---

_Each batch is independent; pick any order. Review and adjust scope per batch before implementing._
