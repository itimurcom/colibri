# M0 / P39 itFeed and engine_items safe cleanup bundle

## Scope

This bundle includes the previously prepared P38 `itFeed` cleanup and the next P39 `engine_items` safe cleanup.

Changed runtime files:
- `SKEL80/classes/blocks/itFeed.class.php`
- `public/engine/core/units/items/engine_items.php`

## P38 part

`itFeed.class.php` cleanup is intentionally visual and safe:
- removed the stale CRC comment block;
- removed decorative separator comment lines.

No feed SQL, payload, button HTML, public method names, or AJAX more/fewer flow were changed.

## P39 part

`engine_items.php` cleanup is intentionally small and safe:
- removed unused `global $_USER` and `$result = NULL` from `get_items_slider(...)`;
- removed unused `$result = NULL` from `get_item_panel(...)`;
- removed unused `$admin_code` placeholder from `get_item_panel(...)`;
- removed unused `$cat_cat` global and `$result = NULL` from `get_items_feed_row(...)`;
- removed a dead `true ? ... : NULL` ternary around item price rendering.

No item route, item URL helper, markup structure, feed SQL, or item editor flow was changed.

## Why this bundle exists

The separate P38 archive had delivery issues in the UI. This bundle carries P38 together with P39 so the safe `itFeed` cleanup is not lost.

## Manual checks

After applying:
- catalog page;
- item page;
- feed more/fewer buttons;
- item price badge;
- item slider/gallery.
