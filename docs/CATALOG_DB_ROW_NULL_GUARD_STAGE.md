# M0 / P78 catalog DB row / null guard stabilization bundle

## Goal
Stabilize the catalog item/category/object/block runtime after the P74-P77 editor and upload stabilization chain. The patch focuses on places where a missing DB row, incomplete item/category payload, empty session filter, or unavailable user object could emit PHP warnings/fatals into public catalog pages or AJAX responses.

## Runtime scope
Changed files:
- `public/engine/core/classes/items/itItem.class.php`
- `public/engine/core/classes/items/itCategory.class.php`
- `public/engine/core/classes/wizards/itObject.class.php`
- `SKEL80/classes/blocks/itBlock.class.php`
- `public/engine/core/units/items/engine_items.php`
- `public/engine/core/units/items/engine_filters.php`

## What changed
- Added local guarded row/options helpers in catalog item/category/block runtime files.
- Hardened `itItem` construction, item compile/table rendering, add path, and place/old-place helpers against missing options, missing DB rows, and incomplete row fields.
- Hardened `itCategory` construction, store, sort/tree/prepare recursion, and category remove-parent SQL input against empty rows and missing root nodes.
- Hardened `itObject` construction, store, wizard value update, title/category setters, form/table rendering, and object count lookup against empty options, empty DB rows, and incomplete wizard metadata.
- Hardened `itBlock` options initialization and compile path so an absent block/content row no longer directly reads missing `content_id` or assumes `$_USER` is always an object.
- Hardened catalog item page/feed helpers against incomplete item rows, category relation misses, empty session filters, missing color filters, and invalid price bound query results.
- Preserved legacy function names and output structure for valid rows.

## Preserved behavior
- Public routes and entrypoint names are unchanged.
- Bootstrap/config/env order is unchanged.
- DB schema is unchanged.
- Storage format is unchanged.
- Existing catalog, filter, feed, editor, and item action function names are preserved.
- No files were deleted.
- No logic was moved to new folders/classes.

## Manual checks
After applying, check:
- `/items/` catalog feed;
- a direct item page;
- item filter by color/tag;
- price range selector;
- item sort selector;
- item gallery/color gallery render;
- category tree/admin category controls if used;
- object wizard form display/save;
- logs for `Undefined array key`, `Trying to access array offset on value of type null`, and missing DB-row warnings.
