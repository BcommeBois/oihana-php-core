# Oihana PHP Core — Roadmap (new functions)

Planned additions to the library, grouped in independent batches (one batch ≈ one
reviewable commit: functions + unit tests + PHPDoc). No version targets here — batches
ship whenever they are ready, and the version they land in is whatever `CHANGELOG.md`
is preparing at that moment. Priority order reflects how thin each namespace currently
is and how often the helpers are needed.

> Conventions for every new function:
> - Free function in its own file under `src/oihana/core/<ns>/<name>.php`, namespace `oihana\core\<ns>`.
> - Register the file in `composer.json` `autoload.files` (then `composer dump-autoload`).
> - Full PHPDoc: `@param` / `@return` / `@throws` (if any) / `@example` / `@package` / `@author` /
>   `@since` set to the version currently open in `CHANGELOG.md` under `[Unreleased]`.
> - Unit tests under `tests/oihana/core/<ns>/<Name>Test.php`, namespace `tests\oihana\core\<ns>`, with `use function ...` imports.
> - Update `CHANGELOG.md` `[Unreleased] > Added`.
> - Avoid duplicating native PHP (noted below where relevant).
> - Keep 100% test coverage (`composer coverage:md`) ; one atomic commit per function.

Source of ideas: the sibling JS lib `vegas-js-core` (`src/`). Cross-reference before
implementing ; skip anything already covered by native PHP.

---

## `date/` — the headline

The namespace currently holds two families: a string-oriented one from 1.0.0
(`isDate`, `formatDateTime`, `now`, `isValidTimezone`) and an object-oriented one from
1.0.9 onwards (`addDays`, `isPast`, `isFuture`, `isWeekend`). Everything below belongs
to the second.

**Contract for the whole batch** — settle it once, then apply it without exception:
- input `DateTimeInterface`, output `DateTimeImmutable` (never mutate the argument) ;
- every helper that needs "now" takes a `?DateTimeInterface $now = null` last parameter,
  as `isPast()` / `isFuture()` already do — this is what keeps the tests deterministic ;
- the timezone of the returned value is the one carried by the input.

### Decisions to make before writing a single line

1. **Month overflow.** PHP's `modify()` overflows: `31/01 + 1 month` gives `03/03`, and
   `31/03 - 1 month` gives `03/03` too. Either clamp to the end of the target month
   (`31/01 + 1 month` = `28/02`) or keep the native behaviour. Clamping is what every
   serious date library does, and the raw behaviour stays one `modify()` away.
2. **Calendar time vs elapsed time.** `addDays()` and friends are calendar arithmetic ;
   `addHours()` and friends are absolute durations. Across a DST transition they differ:
   from `28/03/2026 12:00 Europe/Paris`, `+1 day` lands on `29/03 12:00 +02:00` while
   `+24 hours` lands on `29/03 13:00 +02:00`. Both are correct — the split must be
   documented so a caller picks knowingly.
3. **First day of the week.** `startOfWeek()` / `endOfWeek()` need a
   `int $firstDayOfWeek = 1` parameter (ISO-8601 Monday by default, Sunday for the US
   convention). Related: `isWeekend()` hardcodes Saturday + Sunday, which is a cultural
   assumption worth documenting now that a toolkit builds on it.
4. **Timezone in day comparisons.** Two instants can be the same day in one timezone and
   not in another. `isSameDay()` must state which timezone it compares in ; `isToday()`
   inherits that decision.

### Batch 1 — shifting

`addHours` / `addMinutes` / `addSeconds` / `addWeeks` / `addMonths` / `addYears`, plus the
matching `sub*`. Tests must cover month ends (28/29/30/31) and a DST transition in both
directions.

### Batch 2 — boundaries

`startOfDay` / `startOfWeek` / `startOfMonth` / `startOfYear` and the matching `endOf*`.
`endOf*` returns the last representable instant of the period, microseconds included.

### Batch 3 — predicates and gaps

`isToday`, `isSameDay`, `tomorrow` / `yesterday`, `diffInDays` / `diffInHours`, `isLeapYear`.

Design `isSameDay()` first: `isToday()` is a special case of it, and `tomorrow()` /
`yesterday()` read best as `startOfDay()` composed with `addDays( ±1 )`.

`diffIn*` earns its place precisely because the native API is a trap: over a 34-day gap,
`$a->diff( $b )->d` is `3` (days within the month) while `->days` is `34` (the total).
Wrap that, along with sign handling through `invert`.

---

## New namespaces

- `random/` — UUID v4 generate, `randomInt` / `randomFloat` / `randomBool`, `pickRandom`,
  weighted random.
- `functors/` — `compose`, `negate`, `callOrReturn`, `once` (complements `callables/`).
- `chars/` — unicode-aware classification (`isAlpha`, `isDigit`, `isHexDigit`,
  `isIdentifierStart`, …). Lower priority — much overlaps native `ctype_*`.

## Additions to existing namespaces

- `maths/` — `nearlyEquals` (epsilon float compare), `degreesToRadians` / `radiansToDegrees`,
  `fibonacci`, `floorMod` / `wrap` (true modulo), `distance` / `midPoint`, domain constants
  (`EPSILON`, `PHI`, `DEG2RAD`, `RAD2DEG`, `EARTH_RADIUS`).
- `strings/` — `words` (split into words), `pluralize`, similarity (`diceCoefficient`,
  `nGram` / `bigram` / `trigram`), UUID `validateUuid`.
- `objects/` — `entries` / `pairs` (`[[k, v], …]`), `renameProperty`.

---

## Deliberately dropped

Kept here so they do not quietly come back.

- **`daysInMonth`, `daysInYear`, `before`, `after`, `equals`** — one native expression each
  (`format('t')`, `format('L')`, `<`, `>`, `==`). Note that `==` already compares the
  instant across timezones, microseconds included, so `equals()` would add nothing.
- **Calendar grid** (`firstVisibleDay` / `lastVisibleDay` / `visibleDays`) — computes the
  cells needed to paint a month in a UI, trailing days of the neighbouring months included.
  That is presentation, not date arithmetic, and belongs with the JS `colors/`, `easings/`,
  `graphics/` and `dom/` modules already ruled out below.

> Out of scope for a PHP core lib: the JS `colors/`, `easings/`, `graphics/`, `dom/` modules
> (browser / rendering specific).

---

_Each batch is independent; pick any order. Review and adjust scope per batch before implementing._
