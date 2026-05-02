# M0 / P49 itForm2 controlled runtime consolidation bundle

## Goal
Reduce duplicated `itForm2` runtime rendering paths without changing field semantics, storage format, public method names, or form editor behavior.

## Runtime file
- `SKEL80/classes/f2/itForm2.class.php`

## What changed
- `_view_fields()` and `_edit_fields()` now share local helper methods for common row/context/layout preparation.
- More-editor rendering is centralized in `fieldMoreEditorZone(...)`.
- Field layout state is centralized in `fieldLayoutState(...)`.
- Common field row metadata is centralized in `fieldBaseRow(...)`.
- SET-field submitted option detection is centralized in `isSetFieldOptionChecked(...)`.
- Required-field validation message creation is centralized in `fieldValidationError(...)`.
- The old nested `debug_f2_field(...)` function inside `_edit_fields()` was replaced with a class-local `debugFieldRow(...)` helper to avoid a method-local global function declaration.
- `_arguments(...)` now gives the by-reference `$result` parameter a default value to remove the PHP 8 deprecation caused by an optional parameter before a required parameter.

## Preserved behavior
- Existing public method names were not renamed.
- Existing `add_*` method names were not changed.
- Field storage format was not changed.
- Form field add/remove/move flows were not changed.
- `SKEL80/events/f2/*.func.php` was not changed in this patch.
- No aliases were restored.
- No new runtime files or framework layer were added.

## Manual regression checks
After applying this patch, check:
- regular form view;
- form editor mode;
- required field focus/error behavior;
- SET fields with checked options;
- fields with `more` editor enabled;
- adding/removing/moving fields in the editor;
- adding `Выпадающее меню`, `Установки`, `Описание группы полей`, `Вставка кода`.
