# M0 / P31 shared hotspots runtime cleanup bundle

## Scope
This bundle keeps all work inside existing files and existing runtime entrypoints.
It does not add a new framework, new runtime directories or namespace migration.

## Files changed
- `public/.htaccess`
- `docs/BOUNDARY_QUICKSTART.md`
- `docs/LEGACY_MIXED_HOTZONES.md`
- `docs/SHARED_HOTSPOTS_RUNTIME_CLEANUP_STAGE.md`
- `public/engine/core/units/items/engine_items.php`
- `SKEL80/kernel/engine_functions.php`
- `SKEL80/classes/blocks/itFeed.class.php`
- `SKEL80/classes/f2/itForm2.class.php`

## What changed
### `public/.htaccess`
- restored runtime rewrite from `robots.txt` to `robots.php`

### `docs/BOUNDARY_QUICKSTART.md`
- clarified that `public/engine/kernel.path.php` is optional and loaded only when present
- fixed quickstart numbering and added note about dynamic `robots.php`

### `public/engine/core/units/items/engine_items.php`
- removed duplicate `BLOCK_ITEMALL` compilation
- extracted only local repeated helpers for item URL, item markup image and feed filter/order/options
- kept all work in the same file

### `SKEL80/kernel/engine_functions.php`
- reduced repeated environment/server lookup logic into small shared helpers for IP and referer resolution
- kept redirect behavior in the same file without changing the shared-core model

### `SKEL80/classes/blocks/itFeed.class.php`
- reduced duplication between more/fewer button rendering
- reduced duplication in feed row compilation and feed wrapper assembly

### `SKEL80/classes/f2/itForm2.class.php`
- reduced repetitive `add_*` field builders through one local field-definition helper
- reduced repeated stored-form mutation wrappers for insert/up/down field operations

## Intentional non-goals
- no new runtime files beyond the existing project structure
- no controller/dispatcher/registry layers
- no route changes except the missing `robots.txt` rewrite restoration
- no file deletions in this bundle
