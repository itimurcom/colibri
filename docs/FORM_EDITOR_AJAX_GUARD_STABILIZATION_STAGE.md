# M0 / P74 form editor AJAX guard stabilization bundle

## Scope

This stage stabilizes the legacy editable form runtime used by `itForm2` and the `f2_*` AJAX controls. The change is intentionally limited to the form editor lane and does not alter bootstrap, routes, database schema, storage format, or public entrypoint behavior.

## Why this was needed

The form editor still had several PHP 8.x fragile spots:

- `f2_events()` read `$_REQUEST['op']` and `$_REQUEST['kind']` directly.
- `itForm2::init()` accepted `NULL` options but then accessed option keys as an array.
- Inserted form field kinds were trusted directly from request payloads.
- Existing field XML rows could contain incomplete or stale `kind`/collection data.
- Several `itForm2` field-control classes assigned properties that were not declared, which can leak deprecation output into AJAX responses on newer PHP versions.
- `itSet2` read selected values as an array even when the current value was empty/non-array.

These issues are especially risky around adding, editing, moving, and deleting editable form fields such as SELECT, DESC, CODE, SET, NUMBER, and related controls.

## Changed files

- `SKEL80/events/f2/f2_events.func.php`
- `SKEL80/events/f2/f2_change_event.func.php`
- `SKEL80/classes/f2/itForm2.class.php`
- `SKEL80/classes/f2/itArea2.class.php`
- `SKEL80/classes/f2/itAutoSelect2.class.php`
- `SKEL80/classes/f2/itButton2.class.php`
- `SKEL80/classes/f2/itDate2.class.php`
- `SKEL80/classes/f2/itDesc2.class.php`
- `SKEL80/classes/f2/itInput2.class.php`
- `SKEL80/classes/f2/itSelect2.class.php`
- `SKEL80/classes/f2/itSet2.class.php`
- `SKEL80/classes/f2/itTime2.class.php`
- `SKEL80/classes/f2/itUpGal2.class.php`

## What changed

### Request guard for form-editor AJAX events

`f2_events()` now reads the operation through a guarded helper and returns `false` when the request is not a form-editor event. Field insertion now normalizes the requested field kind before storing it.

### Field kind normalization

`itForm2` now has a single field-kind normalizer. It keeps valid existing kinds intact and falls back to `INPUT` for missing, malformed, or stale values.

This protects add/edit paths from broken request payloads without changing the stored structure for valid fields.

### Safer `itForm2` initialization

`itForm2::init()` now treats non-array options as an empty array before reading option keys. This protects places that intentionally call `new itForm2()` for temporary modal/control forms.

### Safer insert/change paths

Field insertion now accepts only normalized kinds and handles non-array data. Field editing now exits safely when stored `fields_xml` is empty or not an array.

### Declared field-control properties

The `itForm2` control classes now declare the properties they already assign at runtime. This is a PHP 8.x compatibility stabilization only; it does not change rendering logic.

### Safer SET selected-value handling

`itSet2` now checks that the current selected-value payload is an array before reading individual selected keys.

## Explicit non-goals

This patch does not:

- replace `itForm2` with a new form framework;
- change routes, public entrypoints, bootstrap, config, or env constants;
- change DB schema or saved XML/array format;
- rewrite editor UI;
- remove files;
- perform aggressive cleanup outside the form editor lane.

## Manual checks after applying

Check in the admin/editor UI:

1. Add a new editable form field.
2. Add `Выпадающее меню` / SELECT.
3. Add `Описание группы полей` / DESC.
4. Add `Вставка кода` / CODE.
5. Edit an existing field and save.
6. Delete an existing field.
7. Move a field up/down.
8. Watch browser console and Apache/PHP logs for PHP warning/deprecation HTML leaking into AJAX responses.

## Notes for the next patch

Good next candidates:

- continue with `public/ed_field.php` request guard reduction in one bounded lane;
- stabilize `SKEL80/events/editor/editor_events.func.php` upload/request access;
- audit remaining dynamic-property hotspots outside `classes/f2` only after this form editor smoke test passes.
