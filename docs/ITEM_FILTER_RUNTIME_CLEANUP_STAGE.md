# M0 / P62 item filter runtime cleanup bundle

## Goal
Reduce duplicate color/filter selector construction in the item/catalog runtime without changing item routes, filter session keys, or feed behavior.

## Runtime scope
Changed file:
- `public/engine/core/units/items/engine_filters.php`

## What changed
- Admin item color selector rendering now uses shared local helpers for:
  - event payload data;
  - selected-state class;
  - color span rendering;
  - clear-color span rendering.
- Public item color selector rendering now uses one local path for item color spans.
- Item sort options are now defined in one local helper.
- Price selector DB min/max calculation is isolated in one local helper.

## What intentionally did not change
- `$_SESSION['filter']` keys remain unchanged.
- `select_filter(...)`, `select_item_color(...)`, and `sort_price(...)` JS entrypoints remain unchanged.
- Item routes and catalog URLs remain unchanged.
- Feed SQL behavior is not changed.
- No new runtime files or framework layer were added.

## Manual checks
After applying:
- catalog page opens;
- color filter can be selected and cleared;
- item-admin color selector still toggles item colors;
- price slider renders;
- price sort selector works;
- item page color selector still links back to catalog.
