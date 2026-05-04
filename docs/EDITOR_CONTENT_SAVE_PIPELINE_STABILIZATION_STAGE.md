# M0 / P77 editor content save pipeline stabilization bundle

## Goal
Stabilize the legacy WYSIWYG/content editor save pipeline after the P73R-P76 request/upload guard chain without changing bootstrap, routes, storage format, DB schema, public entrypoints, or the old editor UI contract.

## Runtime scope
Changed files:
- `SKEL80/events/editor/editor_events.func.php`
- `SKEL80/classes/editor/itEditor.class.php`

## What changed
- Added guarded request/data/upload helpers inside `editor_events.func.php` so editor AJAX operations no longer read optional `$_REQUEST[...]` / `$_FILES[...]` keys directly.
- Guarded content save/update operations: `ed_text`, `ed_title`, `add_content`, `ed_change`, `add_ed_media`, `show_as`, `status`, `category`, `moderate`, `datetime`, `content_type`, `start_datetime`, and `finish_datetime`.
- Guarded editor avatar upload/removal operations: `ed_add_avatar`, `ava`, and `ava_x`.
- Guarded editor gallery operations: `add_ed_gallery`, `gal_add`, `gal_x`, `gal_up`, `gal_down`, `gal_n`, `gal_link`, and `gal_text`.
- Hardened `itEditor::_redata()` by reusing the shared encrypted payload decoder when available and returning an empty array for missing/malformed payloads.
- Declared existing `itEditor` runtime properties to reduce PHP 8.x dynamic-property deprecation output in AJAX responses.
- Hardened `itEditor` construction/storage initialization when the DB row or editor storage column is missing or malformed.
- Hardened editor field movement, zoom switching, related-content operations, cache/status checks, and `_consolidate()` against missing/non-array storage.
- `itEditor::store()` now returns `false` and skips DB writes when no valid record id/data array is available instead of trying to update a broken record.

## Preserved behavior
- Public routes and entrypoint names are unchanged.
- Bootstrap/config/env order is unchanged.
- DB schema is unchanged.
- Editor storage format is unchanged.
- Existing operation names and response shapes are preserved.
- No files were deleted.
- No logic was moved to new folders/classes.

## Manual checks
After applying, check:
- inline WYSIWYG text save;
- add/remove/move text editor blocks;
- add/change media block;
- title edit;
- content add/status/category/moderation controls;
- title/avatar upload and avatar removal;
- editor gallery add/delete/move/caption/link operations;
- AJAX responses for PHP warnings/deprecations mixed into JSON;
- Apache/PHP logs for `Undefined array key`, malformed `unserialize`, missing `$_FILES`, and invalid DB-row warnings.
