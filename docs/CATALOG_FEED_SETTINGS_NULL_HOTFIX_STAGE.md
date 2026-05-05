# M0 / P84 catalog feed / settings null regression hotfix bundle

## Scope

This hotfix stabilizes two runtime regressions reported after the category/object editor guard line:

- catalog feed rendering failed when `ready_val()` received a temporary expression instead of a mutable variable;
- `/en/settings/` attempted to create settings rows with a NULL `value`, which violates the current database schema.

## Files changed

- `public/engine/core/units/items/engine_items.php`
- `public/engine/core/engine_settings.php`
- `SKEL80/classes/system/itSettings.class.php`
- `PROJECT_PATCHLOG.md`
- `docs/CATALOG_FEED_SETTINGS_NULL_HOTFIX_STAGE.md`

## Runtime notes

- `get_items_feed()` now normalizes the optional feed view through a local variable before calling `ready_val()`.
- Adjacent catalog feed helpers now avoid passing function-call or ternary results directly into `ready_val()`.
- `settings_request_value()` now returns an empty string for absent request values, which prevents password-panel form fields from triggering settings-row creation by accident.
- `itSettings` now normalizes missing setting defaults to an empty string before insert, matching the non-null `value` database column.

## Boundaries preserved

- No bootstrap/config/env changes.
- No route changes.
- No DB schema changes.
- No storage format changes.
- No public entrypoint changes.
- No file removals.
