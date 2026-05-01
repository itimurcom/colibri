# M0 / P43 item admin events structural consolidation

## Goal

Move the item admin event file from small duplicate cleanup to a clearer local construction model without introducing a controller, dispatcher, registry, namespace, or new runtime files.

## Runtime scope

Changed file:
- `public/engine/core/units/items/events/engine_add_items_event.php`

## What changed

The file now has a small local item-event toolkit for repeated UI construction:
- toggle button assembly
- modal + form title assembly
- modal OK/Cancel button assembly
- modal trigger button assembly
- item identity inputs for `serie` and `version`
- direct link button assembly for buy/order/contact actions

## What was removed

The body of `get_rewind_event()` after the first unconditional `return;` was removed.
That code was unreachable in the current runtime and could not affect behavior.

## What stayed unchanged

- Existing public function names were preserved.
- Existing item `op` values were preserved.
- Item routes were not changed.
- `itForm2` was not changed.
- Shared core was not changed.
- No files were deleted.

## Why this is more fundamental than the previous small pass

P42 only reduced duplicate toggle/modal pieces.
P43 standardizes how this file builds item admin UI fragments while still staying in the same file and preserving the old public API.
