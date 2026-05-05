# M0 / P83 category object editor action guard stabilization bundle

## Scope

This patch continues the M0 runtime stabilization lane after the public navigation, sitemap, admin, and moderation guard passes.

The patch focuses on legacy category/object editor actions and the destructive moderation `killall` request path:

- `SKEL80/events/editor/category_events.func.php`
- `SKEL80/events/editor/get_add_category_event.func.php`
- `SKEL80/events/editor/get_category_parent_event.func.php`
- `SKEL80/events/editor/get_category_title_event.func.php`
- `SKEL80/events/editor/get_category_x_event.func.php`
- `SKEL80/events/objects/object_events.func.php`
- `SKEL80/events/objects/get_add_object_event.func.php`
- `SKEL80/events/objects/get_object_category_event.func.php`
- `SKEL80/events/objects/get_object_form_event.func.php`
- `SKEL80/events/objects/get_object_select_event.func.php`
- `SKEL80/events/objects/get_object_text_event.func.php`
- `SKEL80/events/objects/get_object_title_event.func.php`
- `SKEL80/events/objects/get_object_wizard_row_event.func.php`
- `SKEL80/events/blocks/killall_contents_request.func.php`
- `public/engine/core/classes/items/itCategory.class.php`
- `public/engine/core/classes/wizards/itObject.class.php`

## What changed

- Added guarded request reads for category/object action handlers.
- Hardened encrypted category event payload decoding and operation dispatch.
- Added early validation for category `rec_id`, parent id, table name, and object target ids.
- Guarded category/object modal render helpers against incomplete rows and missing prepared arrays.
- Fixed an old PHP 8 fatal-risk in `get_category_title_event()` by using the string key `'data'` instead of an undefined constant token.
- Corrected the `get_add_object_event()` input metadata typo from `lablel` to `label`.
- Preserved the old object form-update fallback but now prefers the submitted `rec_id` as the object target before falling back to the category field.
- Hardened destructive moderation basket cleanup by validating `table_name` and `status`, initializing the DB prefix safely, and avoiding the previously undefined local `$db` usage.

## Explicit non-goals

- No bootstrap/config/env changes.
- No route changes.
- No public entrypoint changes.
- No DB schema changes.
- No storage format changes.
- No namespace/framework/dispatcher changes.
- No file removals.

## Notes

This is still a runtime guard pass. It does not redesign the category/object editor architecture and does not move logic between subsystems. The goal is to stop malformed request payloads, missing prepared rows, or stale modal submissions from producing PHP warnings/fatals in admin/editor flows.

## Suggested manual smoke checks

- Add category.
- Change category parent.
- Remove category.
- Edit category title.
- Add object.
- Edit object title/category/value/select field.
- Open object form modal and save object wizard fields.
- Use moderation basket `killall` only on a safe local/dev dataset.
