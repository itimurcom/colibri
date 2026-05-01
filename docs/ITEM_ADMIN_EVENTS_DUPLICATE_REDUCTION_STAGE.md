# M0 / P42 item admin events duplicate reduction

## Goal
Reduce repeated project-side item admin event code without changing public function names, routes, form storage, or shared-core behavior.

## Runtime scope
Changed file:
- `public/engine/core/units/items/events/engine_add_items_event.php`

## What changed
The file now uses local helper functions for repeated patterns:
- toggle admin buttons for `is_shop`, `is_replicant`, `is_econom`, `is_new`;
- modal creation with size/animation;
- repeated OK/Cancel button assembly;
- modal form compile + trigger button output;
- category selector option assembly.

## What intentionally did not change
- no new runtime files;
- no new folders;
- no namespace migration;
- no controller/registry/dispatcher layer;
- no rename of existing public `get_*_event(...)` functions;
- no change to item routes or item mutation `op` values.

## Preserved behavior
The existing event functions still exist and remain the public API for the rest of the project:
- `get_item_shop_event(...)`
- `get_item_replicant_event(...)`
- `get_item_econom_event(...)`
- `get_item_new_event(...)`
- `get_item_title_event(...)`
- `get_item_x_event(...)`
- `get_item_add_event(...)`
- `get_item_articul_event(...)`
- `get_buy_item_event(...)`
- `get_order_item_event(...)`
- `get_item_calc_event(...)`
- `get_price_item_event(...)`

## Test focus
After applying the patch, test item admin UI:
- shop / replicant / econom / new toggles;
- edit title modal;
- remove item modal;
- add item modal;
- articul/category modal;
- price modal;
- buy/order/calc buttons on item pages.
