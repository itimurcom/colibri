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

## M0 / P5 itMySQL null-safe json decode hotfix
- Fixed legacy DB value normalization in `SKEL80/classes/system/itMySQL.class.php`.
- Added `normalize_db_value()` to keep `NULL` values untouched before `html_entity_decode()` / `json_decode()`.
- Reused the same normalization path in `get_rec_from_db()`, `get_arr_from_db()`, and `request()`.
- Expected result: startup/runtime no longer emits `json_decode(): Passing null to parameter #1 ($json)` deprecations from `itMySQL` when nullable DB columns are read.
- Next step: if more deprecations remain, continue the same runtime hotfix sweep class-by-class.
