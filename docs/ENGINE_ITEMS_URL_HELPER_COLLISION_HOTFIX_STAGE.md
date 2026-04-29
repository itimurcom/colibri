# M0 / P32 engine items URL helper collision hotfix

## What happened

`public/engine/core/units/items/engine_items.php` introduced a local helper named `get_item_url(...)`, but the project already had a global `get_item_url(...)` in `public/engine/core/engine_urls.php`.

This caused a fatal redeclaration error during runtime.

## What this hotfix does

- Renames the local helper in `engine_items.php` from `get_item_url(...)` to `get_item_runtime_url(...)`.
- Updates local usages inside the same file.
- Leaves the existing shared `get_item_url(...)` in `engine_urls.php` untouched.

## What intentionally did not change

- No other item rendering logic was changed.
- No routes or URL generation rules were changed.
- No files were deleted.

## Next step

Continue `engine_items.php` cleanup, but treat project-global helper names as reserved before introducing any local helper in hotspot files.
