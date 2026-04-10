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


## M0 / P11 feed query limit explicit override
- Fixed `itFeed` SQL limit behavior: an explicit feed `limit` is now used in database queries, while global `FEED_LIMIT` remains only a fallback when no explicit limit is provided.
- `mailing_history_panel()` now passes an explicit limit based on `FEED_NUMBER['mailing_history']`, so the mails section stops over-fetching rows from the database.
- No feed rendering logic was removed; the patch changes only query-limit resolution and the mailing-history feed wiring.


## M0 / P12 runtime stabilization bundle
- Added runtime bootstrap compatibility layer with file logging, timezone baseline, safer session start, and shutdown/deprecation handling.
- Switched `public/config.php` to layered configuration defaults with optional local/secret overlays and environment overrides.
- Made runtime discovery deterministic in `SKEL80/kernel/core.php` by sorting glob results and using safer include/autoload behavior.
- Replaced deprecated `strftime()` hot spots with a compatibility formatter across the shared kernel and project overlay mail/date paths.
- Replaced legacy `each()` loops in bundled mailer dependencies.
- Added NULL-safe JSON/HTML decode handling in `itMySQL` and `isJson()`.
- Converted legacy short tags in SKEL80/public PHP files to `<?php` so runtime no longer depends on `short_open_tag`.
- Added explicit properties for frequently hit dynamic-property legacy classes used during runtime.

## M0 / P13 explicit core overlay boundaries bundle
- Formalized runtime ownership model: shared core, project bootstrap, project overlay, project delivery, mixed hotspots.
- Added runtime boundary map and lifecycle contract under `SKEL80/kernel/runtime_boundaries.php` and `SKEL80/kernel/runtime_contract.php`.
- Added `public/engine/overlay_contract.php` with Colibri-specific responsibilities, extension points and hotspot declarations.
- Added boundary manifests directly inside key directories so a developer can open a folder and immediately see ownership.
- Updated `SKEL80/run.php` to load and expose boundary/contract data during bootstrap.
- Added Stage 3 docs: platform contract, boundary map, extension points and mixed hotzones.
