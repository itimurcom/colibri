# M0 / P47 catalog/feed runtime consolidation bundle

## Goal
Consolidate the catalog/items feed runtime path without adding a new feed framework, controller layer, registry, namespace, or runtime folders.

## Runtime scope
Changed files:
- `public/engine/core/units/items/engine_items.php`
- `SKEL80/classes/blocks/itFeed.class.php`

Inspected but intentionally not changed:
- `public/more.php`

## What changed in `engine_items.php`
- Catalog feed SQL construction is now grouped in `get_items_feed_sql(...)`.
- View-specific feed filters are isolated in `get_items_feed_view_sql(...)`.
- Feed object creation is centralized in `get_items_compiled_feed(...)`.
- Fewer/anchor feed handling is centralized in `get_items_fewer_feed_code(...)`.
- Feed title rendering is centralized in `get_items_feed_title_code(...)`.
- `get_items_feed(...)` now reads as orchestration instead of mixed SQL + feed + render logic.

## What changed in `itFeed.class.php`
- Shared run-buffer reset is centralized in `reset_run_state()`.
- Loop restart logic is centralized in `restart_loop_if_needed()`.
- Legacy `table_name` / `rec_id` row context assembly is centralized in `with_row_context(...)`.
- `weight_run()`, `onefield_run()`, and `run()` now reuse those local helpers instead of keeping the same control code in parallel.

## What did not change
- No feed storage format changes.
- No URL changes.
- No item route changes.
- No `public/more.php` behavior changes.
- No `itForm2` changes.
- No new runtime files.
- No file deletions.

## Manual regression checks
After applying:
- catalog page opens;
- item list renders;
- filter by color/tags still works;
- sort by price up/down still works;
- `more` loads the next feed block;
- anchor/fewer flow still renders correctly when opened from feed;
- item cards still open item pages.
