# Project patch log

## M0 / P1 platform contract and runtime baseline
- Formalized the shared-kernel/runtime contract without changing the historical `SKEL80/` + `public/` topology.
- Added `SKEL80/kernel/runtime_contract.php` with explicit phase order, overlay points and precedence model.
- Marked runtime phases directly inside `SKEL80/run.php` and flagged the `public/engine/kernel.php` continuation as the post-run project overlay.
- Added `docs/PLATFORM_CONTRACT.md` as the working contract for shared core vs project overlay and the next modernization steps.
- Expected result: further modernization can target a named platform contract instead of reverse-engineering implicit bootstrap behavior.
- Next step: continue the same patch line with runtime compatibility baseline (`<?`, `each()`, deprecations, config/env hardening, error/logging baseline).

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
