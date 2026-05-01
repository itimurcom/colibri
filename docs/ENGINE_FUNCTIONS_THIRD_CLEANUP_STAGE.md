# M0 / P40 engine_functions third cleanup pass

## Scope

Runtime file:
- `SKEL80/kernel/engine_functions.php`

Docs:
- `docs/BOUNDARY_QUICKSTART.md`
- `docs/LEGACY_MIXED_HOTZONES.md`
- `docs/ENGINE_FUNCTIONS_THIRD_CLEANUP_STAGE.md`

## Goal

Continue direct cleanup of the shared helper dump without adding a new helper layer, namespace, dispatcher, or moving functions to new files.

## Runtime changes

- Removed stale CRC header comments from `engine_functions.php`.
- Removed repeated decorative `//........` separators from the file.
- Reused `skel80_random_from_chars(...)` inside `generate_hash(...)` instead of keeping a second local random-loop implementation.
- Reused `skel80_json_response(...)` inside `print_json(...)` instead of keeping a second inline `json_encode(...)` output branch.

## Preserved behavior

- Public function names are preserved.
- Bootstrap order is unchanged.
- No files were moved.
- No files were deleted.
- `print_json(...)` keeps the existing practical behavior: it writes JSON and returns the print result. The legacy `$stop` argument remains accepted for compatibility.

## Why this is safe

The patch removes local duplication and stale visual noise only. It does not change routing, storage, form handling, feed handling, or editor behavior.
