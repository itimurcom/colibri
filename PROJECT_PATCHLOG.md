# Project patch log

## M0 / P6 avatar null compatibility for editor save
- Fixed legacy editor save path for records where DB column `avatar` is `NOT NULL`.
- `itEditor::store()` now normalizes top-level `avatar` from `NULL` to an empty string before full-record update.
- `ava_x` event now clears avatar with an empty string instead of `NULL`.
- Expected result: `public/ed_field.php` stops returning PHP fatal HTML for editor AJAX saves, so frontend JSON parsing works again.
- Next step: if another AJAX endpoint still returns HTML instead of JSON, inspect that endpoint's backend fatal separately.

## M0 / P9 integer toggle save for item flags
- Fixed moderator toggle save for item integer flags in `public/ed_field.php`.
- `is_new`, `is_econom`, `is_shop`, and `is_replicant` now persist explicit `1/0` integers instead of raw PHP booleans.
- Expected result: repeated toggles no longer try to write an empty string into integer DB columns like `colibri_items.is_replicant`.
- Next step: if another toggle fails similarly, inspect that endpoint for boolean-to-string persistence before touching DB helpers again.

- M0 / P15 mail console and date compatibility bundle
  - fixed `itModal`, `itButton`, `itForm2` dynamic property deprecations affecting the mail section and price calculator
  - added `strftime()` compatibility helpers in `SKEL80/kernel/engine_functions.php`
  - replaced deprecated `strftime()` usage in `public/engine/core/engine_mails.php`

- M0 / P18 mixed-hotspot reduction bundle
  - reduced coupling in `itFeed` by adding explicit limit/need_total/query helpers and lazy-safe total count behavior
  - refactored `public/more.php` into named-operation dispatch + safe feed payload render path
  - replaced per-row mail history modal/forms with one shared preview modal in `engine_mails.php`
  - extracted calculator/mail-status helper handlers in `public/ed_field.php`
  - added shared runtime helpers for encrypted payload decode and JSON responses

- M0 / P20 itForm2 cleanup refactor bundle
  - removed legacy comment noise and dead commented code from `SKEL80/classes/f2/itForm2.class.php`
  - introduced shared helpers for field/button collection insert/sort/move logic inside `itForm2`
  - reformatted alias wrapper methods for cleaner visual structure
  - fixed `_reCaptcha()` session key lookup and corrected button collection move/insert behavior

- M0 / P71 router/language request baseline stabilization bundle
  - stabilized legacy router path parsing for URLs with and without trailing slash
  - added guarded request reads in itSite for controller/view/lang/table/record defaults
  - hardened itLang against stale session language values and incomplete language rows
  - fixed language switch link generation for URLs without an existing language prefix
