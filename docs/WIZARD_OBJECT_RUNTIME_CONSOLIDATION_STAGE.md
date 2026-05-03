# M0 / P69 wizard/object runtime consolidation bundle

## Goal
Consolidate project-side wizard/object runtime code without changing wizard public APIs, object form storage, category links, or event operation names.

## Runtime scope
Changed files:
- `public/engine/core/classes/wizards/itWizard.class.php`
- `public/engine/core/classes/wizards/itObject.class.php`

## What changed

### `itWizard.class.php`
- Wizard row rendering now uses local row/context helpers.
- Repeated wizard load/options setup is centralized.
- Repeated `_set_*` methods now use one local row-value setter path.
- `store()` is guarded against non-array internal data before `array_values(...)`.

### `itObject.class.php`
- Object wizard form field assembly now uses local helpers for:
  - input options;
  - select options;
  - per-row context;
  - field dispatch.

## Preserved behavior
- Existing public methods are preserved.
- Existing wizard event operation names are preserved.
- Existing object form operation name is preserved.
- Existing DB tables/fields are preserved.
- Existing `itForm2` calls are preserved.

## What intentionally did not change
- No new runtime files.
- No new framework layer.
- No namespace migration.
- No storage format changes.
- No form field semantic changes.
