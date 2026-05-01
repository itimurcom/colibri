# M0 / P45 itEditor runtime consolidation bundle

## Goal
Reduce duplicated editor runtime paths inside `SKEL80/classes/editor/itEditor.class.php` without changing the public editor API, storage format, or event flow.

## Runtime scope
Changed file:
- `SKEL80/classes/editor/itEditor.class.php`

## What changed
- `_view()` and `_edit()` now share one local field compilation path through `compileEditorStorage(...)`.
- Common editor block context preparation is centralized in `editorBlockRow(...)`.
- Edit-mode first-field bootstrap is centralized in `ensureStorageField(...)`.
- Gallery item move/up/down operations now share one local swap path for `value`, `text`, and `link` arrays.

## What did not change
- Public method names remain unchanged.
- Editor storage format remains unchanged.
- `events()`, `store()`, `cache()`, and container behavior were not rewritten.
- No new runtime files, factories, controllers, registries, or namespaces were added.

## Why this is safe for M0
This patch consolidates repeated logic inside the existing class instead of extracting a new editor layer. The editor still uses the same block classes and the same `_view()` / `_edit()` public entrypoints, but duplicated preparation and gallery movement code is no longer maintained in parallel.

## Manual regression checks
After applying this patch, check:
- public material view with text/image/gallery/media blocks;
- editor mode rendering for the same material;
- moving gallery images up/down;
- moving a gallery image to a different position;
- adding/editing gallery image text and link;
- saving a text block.
