# M0 / P34 engine_functions second cleanup pass

## Scope
Direct cleanup inside `SKEL80/kernel/engine_functions.php`.

## Runtime changes
- Added `skel80_random_from_chars(...)` as a local primitive for repeated random-string generation.
- `generate_new_password(...)` now uses the shared primitive with the same legacy character set.
- `random_str(...)` now uses the same primitive.
- Added `skel80_relative_date_label(...)` for the repeated today/yesterday label checks.
- Added `skel80_local_date_format(...)` for repeated localized date format parts.
- `get_local_date_str(...)` and `get_local_datetime_str(...)` now use those local primitives.

## What was intentionally not done
- No new files.
- No namespace migration.
- No dispatcher/controller/registry layer.
- No change to route/bootstrap topology.

## Manual checks
- places that show localized dates
- generated passwords
- random short codes
- login/logout redirect flow
