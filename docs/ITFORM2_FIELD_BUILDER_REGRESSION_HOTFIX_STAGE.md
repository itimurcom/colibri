# M0 / P37 itForm2 field builder regression hotfix

## Scope

This is a hotfix for regressions after `M0 / P36 itForm2 canonical method names`.

Changed runtime files:

- `SKEL80/classes/f2/itForm2.class.php`
- `SKEL80/events/f2/f2_change_event.func.php`

## Problem

After alias removal, form field creation paths for several editor field types lost part of their old default-data behavior.

Observed broken editor actions:

- `Установки (галочки)`
- `Выпадающее меню`
- `Описание группы полей`
- `Вставка кода`
- field delete action when the form editor attempted to render controls near a malformed list field

The visible fatal was:

```text
array_column(): Argument #1 ($array) must be of type array, null given
```

## Fix

- `add_selector(...)` now uses `SELECT` defaults directly after canonical method migration.
- Newly inserted `SELECT` and `SET` fields now receive safe list metadata:
  - `array`
  - `titles`
  - `values`
- `f2_change_event(...)` now treats missing or malformed `array` data as an empty list before calling `array_column(...)`.

## What was not changed

- no aliases were restored
- no new form layer was added
- no storage format rewrite
- no submit/event flow rewrite
- no file deletion
