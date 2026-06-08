# Oihana PHP Core — Roadmap (new functions)

Planned additions to the library, grouped in independent batches (one batch ≈ one
reviewable commit: functions + unit tests + PHPDoc). Priority order reflects how
thin each namespace currently is and how often the helpers are needed.

> Conventions for every new function:
> - Free function in its own file under `src/oihana/core/<ns>/<name>.php`, namespace `oihana\core\<ns>`.
> - Register the file in `composer.json` `autoload.files` (then `composer dump-autoload`).
> - Full PHPDoc: `@param` / `@return` / `@throws` (if any) / `@example` / `@package` / `@author` / `@since <current dev version>`.
> - Unit tests under `tests/oihana/core/<ns>/<Name>Test.php`, namespace `tests\oihana\core\<ns>`, with `use function ...` imports.
> - Update `CHANGELOG.md` `[Unreleased] > Added`.
> - Avoid duplicating native PHP (noted below where relevant).
> - Keep 100% test coverage (`composer coverage:md`) ; one atomic commit per function.

---

## 🎯 1.1 (inspired by `vegas-js-core`)

Source of ideas: the sibling JS lib `vegas-js-core` (`src/`). Cross-reference before
implementing ; skip anything already covered by native PHP.

### `date/` — full toolkit (the headline)
- `addHours/addMinutes/addSeconds/addWeeks/addMonths/addYears` + matching `subtract*`.
- `startOfDay/startOfWeek/startOfMonth/startOfYear` + `endOf*`.
- `daysInMonth`, `daysInYear`, `isLeapYear`, `diffInDays`/`diffInHours`, `isToday`,
  `tomorrow` / `yesterday`, `isSameDay`, `equals` / `before` / `after`.
- (Stretch) calendar grid: `firstVisibleDay` / `lastVisibleDay` / `visibleDays`.

### New namespaces
- `random/` — UUID v4 generate, `randomInt` / `randomFloat` / `randomBool`, `pickRandom`,
  weighted random.
- `functors/` — `compose`, `negate`, `callOrReturn`, `once` (complements `callables/`).
- `chars/` — unicode-aware classification (`isAlpha`, `isDigit`, `isHexDigit`,
  `isIdentifierStart`, …). Lower priority — much overlaps native `ctype_*`.

### Compléments to existing namespaces
- `maths/` — `nearlyEquals` (epsilon float compare), `degreesToRadians` / `radiansToDegrees`,
  `fibonacci`, `floorMod` / `wrap` (true modulo), `distance` / `midPoint`, domain constants
  (`EPSILON`, `PHI`, `DEG2RAD`, `RAD2DEG`, `EARTH_RADIUS`).
- `strings/` — `words` (split into words), `pluralize`, similarity (`diceCoefficient`,
  `nGram` / `bigram` / `trigram`), UUID `validateUuid`.
- `objects/` — `entries` / `pairs` (`[[k, v], …]`), `renameProperty`.

> Out of scope for a PHP core lib: the JS `colors/`, `easings/`, `graphics/`, `dom/` modules
> (browser / rendering specific).

---

_Each batch is independent; pick any order. Review and adjust scope per batch before implementing._
