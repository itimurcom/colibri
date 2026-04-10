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

## M0 / P10 feed limit performance hotfix
- Reduced global `FEED_LIMIT` from `10000` to `100` in `SKEL80/events/blocks/ini/const.php`.
- Reason: legacy `itFeed` main SQL path still uses global `FEED_LIMIT` for DB batching, so pages with several feeds could request far more rows than the UI actually shows.
- Expected result: initial loading of feed-heavy screens, especially `Письма`, should stop pulling oversized MySQL batches.
- No files were deleted. No routing, rendering, or feed business logic was rewritten.
- Next step: if performance is still not acceptable, move from global cap to per-feed SQL limit using `itFeed::$limit`.

## M0 / P16 real feed total count bundle
- Fixed `SKEL80/classes/blocks/itFeed.class.php` so SQL feeds now load limited result rows but calculate `count_all()` from a separate count query without `LIMIT`.
- Added explicit limit handling with fallback to global `FEED_LIMIT` only when a feed does not define its own `limit`.
- Updated `public/engine/core/engine_mails.php` so `mailing_history` feeds pass their explicit limit from `FEED_NUMBER`.
- Expected result: feed-heavy screens such as `Письма` keep limited row loading while total counts and pagination logic stop collapsing to the current batch size.
