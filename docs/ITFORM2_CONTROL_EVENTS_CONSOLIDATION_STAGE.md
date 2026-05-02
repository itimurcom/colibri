# M0 / P54 itForm2 control events consolidation bundle

## Goal
Consolidate repeated form-field control event assembly after P53 without changing `itForm2.class.php`, field storage format, or add/remove/move semantics.

## Runtime scope
Changed files:
- `SKEL80/events/f2/f2_button_set.func.php`
- `SKEL80/events/f2/f2_up_field_event.func.php`
- `SKEL80/events/f2/f2_down_field_event.func.php`
- `SKEL80/events/f2/f2_new_field_event.func.php`
- `SKEL80/events/f2/f2_x_field_event.func.php`

## What changed
- Shared reload JavaScript assembly moved to `f2_control_reload_js(...)`.
- Shared hidden-data assembly moved to `f2_control_add_data(...)`.
- Up/down ajax buttons now use one local helper.
- New/remove modal flow now uses shared local modal/form/button helpers.
- Old commented wrapper fragments in `f2_button_set(...)` were removed.

## Preserved behavior
- Existing public function names are preserved.
- Existing operation names are preserved:
  - `up_f2_field`
  - `down_f2_field`
  - `f2_field`
  - `f2_x`
- Field add/delete/move semantics are unchanged.
- `itForm2.class.php` is not changed.
- No new runtime files were added.
- No files were deleted.

## Manual checks
After applying:
- add field;
- delete field;
- move field up;
- move field down;
- edit field settings;
- verify the form editor reloads after every control action.
