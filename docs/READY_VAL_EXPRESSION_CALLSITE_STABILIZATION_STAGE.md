# M0 / P85 ready_val expression callsite stabilization bundle

## Scope

This stage closes the broader PHP runtime failure class exposed by the catalog-feed regression hotfix: legacy `ready_val()` accepts its first argument by reference, so PHP cannot safely call it with temporary values such as helper return values, ternary expressions, or nested array lookups.

## Files changed

- `SKEL80/kernel/core.php`
- `public/ed_field.php`
- `public/engine/core/units/users/events/engine_admin_events.php`
- `public/engine/core/units/items/engine_filters.php`
- `public/engine/core/classes/items/itItem.class.php`
- `public/engine/core/classes/items/itCategory.class.php`
- `public/engine/core/classes/wizards/itObject.class.php`
- `SKEL80/events/editor/category_events.func.php`
- `SKEL80/events/editor/get_content_date_event.func.php`
- `SKEL80/classes/blocks/itFeed.class.php`
- `SKEL80/classes/blocks/itBlock.class.php`
- `PROJECT_PATCHLOG.md`
- `docs/READY_VAL_EXPRESSION_CALLSITE_STABILIZATION_STAGE.md`

## Runtime notes

- `ready_val()` is preserved unchanged for legacy callers that pass mutable variables or array offsets.
- `ready_value()` was added as a by-value companion for expressions that cannot be passed by reference.
- Unsafe expression call-sites were switched to `ready_value()` in catalog, object, block, editor/category, and admin-login helper boundaries.
- This prevents PHP fatal errors of the form `Argument #1 could not be passed by reference` without changing the meaning of valid values or defaults.

## Boundaries preserved

- No bootstrap/config/env changes.
- No route changes.
- No DB schema changes.
- No storage format changes.
- No public entrypoint changes.
- No file removals.
