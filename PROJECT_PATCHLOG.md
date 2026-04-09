# Project patch log

## M0 / P2 runtime compatibility baseline
- Kept the historical shared-kernel + project-overlay model intact and hardened runtime behavior for modern PHP.
- Added `SKEL80/kernel/runtime_compat.php` and wired it into `SKEL80/run.php` to apply runtime settings and install error/deprecation/fatal logging handlers.
- Reworked `public/config.php` to support optional ignored secrets overlays (`config.secrets.php`, `config.secrets.local.php`) plus environment-variable overrides.
- Added `public/config.secrets.example.php` and `public/logs/.gitignore` for local runtime log handling.
- Replaced legacy `<?` short open tags with `<?php` across the runtime PHP surface so the project no longer depends on `short_open_tag`.
- Removed PHP 8-incompatible `each()` usage from bundled PHPMailer sources and normalized mailer debug/log defaults.
- Normalized the shared-kernel include in `public/engine/kernel.php` to use an absolute path derived from `__DIR__` instead of a fragile relative string.
- Expected result: Colibri keeps the original SKEL80 shared-core architecture but becomes much more predictable on modern PHP, with clearer secret handling and runtime diagnostics.
- Next step: continue with explicit shared/project boundary cleanup and targeted deprecation follow-up where runtime logs point to remaining hot spots.

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
