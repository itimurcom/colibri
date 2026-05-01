# M0 / P44 itEditor constructor normalization

## Goal
Normalize the `itEditor` constructor without changing editor public API, storage format, render flow or event flow.

## Runtime file
- `SKEL80/classes/editor/itEditor.class.php`

## What changed
The constructor previously had two separate branches:
- scalar constructor arguments
- options-array constructor arguments

Both branches initialized the same runtime properties with duplicated code. The patch keeps both calling styles, but normalizes them through one local constructor path.

## Preserved behavior
- public constructor signature is unchanged
- old scalar constructor style is still supported
- options-array constructor style is still supported
- editor storage and record references are unchanged
- URL regeneration behavior is unchanged
- public methods were not renamed or removed

## What was removed
Only duplicated constructor assignment code was removed.

No files were deleted; no remove manifest is required.
