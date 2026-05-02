# M0 / P53 itForm2 events controlled cleanup bundle

## Goal

Continue `itForm2` cleanup in the event layer after the class-level P49 consolidation, without touching field storage format, add/remove/move semantics, or public `itForm2` methods.

## Runtime scope

Changed files:

- `SKEL80/events/f2/f2_change_event.func.php`
- `SKEL80/events/f2/f2_events.func.php`

## What changed

`f2_change_event.func.php` now has local helper paths for:

- localized form labels;
- SELECT / SET option metadata normalization;
- SELECT / SET title/value textarea extraction;
- numeric min/max/multi controls;
- editor settings set assembly.

`f2_events.func.php` now has local helper paths for:

- JSON responses;
- form container reload;
- state switch reload;
- simple action responses.

## Bugfix included

The numeric field settings modal now posts the max value through the `max` field name instead of reusing `min` for the `F2_MAX` control.

## What intentionally did not change

- no `add_*` method rename;
- no field storage format change;
- no add/delete/move behavior change;
- no `itForm2.class.php` changes in this patch;
- no new runtime files;
- no files removed.

## Manual regression checks

After applying:

- add field: `Установки`;
- add field: `Выпадающее меню`;
- add field: `Описание группы полей`;
- add field: `Вставка кода`;
- edit SELECT/SET field options;
- edit NUMBER field min/max/multi settings;
- delete field;
- move field up/down;
- switch form edit/view state;
- reload form editor block.
