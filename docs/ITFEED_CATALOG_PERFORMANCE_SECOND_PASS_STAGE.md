# M0 / P57 itFeed catalog performance second pass

## Goal
Stabilize and slightly optimize catalog/feed runtime after the previous item/catalog vertical passes without changing routes, storage format, item URL behavior, or the public feed endpoint.

## Runtime scope
Changed files:
- `SKEL80/classes/blocks/itFeed.class.php`
- `public/engine/core/units/items/engine_items.php`

Checked but not changed:
- `public/more.php`

## What changed

### `itFeed.class.php`
- Feed row callback resolution now happens once per run loop instead of once per row.
- Missing callback now returns safely after logging the existing error message instead of allowing a later `call_user_func(NULL, ...)` path.
- Legacy row context now guards missing `id` before assigning `rec_id`.
- Onefield startup now checks that the request source is an object before calling `mysqli_fetch_assoc(...)`.
- `step(...)` now returns `NULL` for invalid DB request sources instead of calling `mysqli_fetch_assoc(...)` on non-result data.
- `compile(...)` no longer passes an unused argument into `get_feed_arr(...)`.

### `engine_items.php`
- Item-card animation no longer reads `$_REQUEST['rec_id']` directly.
- `is_for_sale(...)` now safely handles non-array/null rows.

## What did not change
- item routes;
- item URL behavior;
- feed SQL semantics;
- `public/more.php` response behavior;
- `itForm2`, `itEditor`, `itMySQL`;
- storage format;
- public method names.

## Manual regression checks
- `/items/` and language-prefixed item feeds;
- category feed;
- more/fewer button;
- `?anchor=` navigation;
- item card animation when opening from an anchor;
- item pages with sale and non-sale categories;
- PHP logs for `itFeed.class.php` and `engine_items.php` warnings.
