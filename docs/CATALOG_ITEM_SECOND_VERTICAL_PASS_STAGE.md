# M0 / P51 item/catalog second vertical pass

## Goal
Continue the item/catalog vertical slice cleanup after P47 without changing routes, URL behavior, storage format, or feed framework behavior.

## Runtime scope
Changed file:
- `public/engine/core/units/items/engine_items.php`

Checked but not changed:
- `SKEL80/classes/blocks/itFeed.class.php`
- `public/more.php`

## What changed
`engine_items.php` now has clearer local construction points for the item/catalog runtime flow:
- item image container creation;
- item panel admin actions;
- item panel action controls;
- item page metadata assignment;
- item gallery title/caption context;
- feed price filters;
- item card animation state;
- item card price/sale badge;
- item series feed SQL.

## What intentionally did not change
- no item routes changed;
- no item URL behavior changed;
- no storage format changed;
- no `itForm2` changes;
- no `itEditor` changes;
- no new feed framework;
- no new runtime files;
- no file deletion.

## Manual regression checks
After applying this patch, check:
- catalog feed page;
- item cards;
- item page open;
- item image slider;
- item color gallery;
- `more` / `fewer` feed controls;
- `?anchor=` flow;
- price sort and color/tag filters;
- item structured metadata output.
