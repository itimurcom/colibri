# M0 / P75 ed_field AJAX request guard stabilization bundle

## Scope

This stage stabilizes `public/ed_field.php`, the shared AJAX/runtime entrypoint used by editor operations, customer/login helpers, item controls, slider controls, settings updates, and upload-backed moderator actions.

The patch is intentionally bounded to one runtime zone. It does not change bootstrap order, routes, DB schema, storage format, public entrypoint names, or the legacy event dispatch sequence.

## What changed

- Added local guarded request helpers inside `public/ed_field.php` for scalar, integer, flag, encrypted payload, data-array, and upload checks.
- Replaced direct `$_REQUEST[...]` reads in the handled `ed_field.php` operation paths with guarded helper calls.
- Reused the existing shared `skel80_decode_encrypted_array()` helper for encrypted serialized request payloads.
- Guarded `openclose` and `tab` payload handling so malformed/missing encrypted data no longer produces array-key warnings before the JSON response.
- Guarded upload iteration in `ed_field_uploaded_files()` so empty/no-file AJAX operations do not read missing `$_FILES[DEFAULT_FILES_NAME]` structures.
- Guarded background upload failure reporting so missing upload metadata does not create warnings while building the error message.

## Why this was done

`public/ed_field.php` is one of the most sensitive legacy AJAX endpoints. When PHP warnings leak into its responses, the frontend often receives HTML/warning text instead of JSON or a clean redirect. This stage reduces that risk without introducing a new dispatcher, framework layer, or compatibility architecture.

## Explicit non-goals

- No bootstrap/config/env changes.
- No route changes.
- No DB schema changes.
- No storage format changes.
- No file removals.
- No migration of logic to new folders/classes.
- No rewrite of the old `ed_field.php` operation model.

## Manual checks after applying

Check these flows after applying the patch:

1. Customer AJAX login/PIN flow.
2. Item filter/sort/color filter operations.
3. Inline user profile edit operations.
4. Item add/edit operations.
5. Slider add/delete/title/href operations.
6. Background upload operation with and without selected file.
7. Any frontend call that expects JSON from `public/ed_field.php`.
8. Apache/PHP logs for `Undefined array key`, `Trying to access array offset`, `unserialize`, and `$_FILES` warnings.
