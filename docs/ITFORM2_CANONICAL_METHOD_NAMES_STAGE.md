# M0 / P36 itForm2 canonical method names

## Goal
Remove legacy synonym method names from `itForm2` and normalize project call sites to one canonical method name per field/button type.

## Runtime scope
Changed runtime code stays inside the existing project/shared tree. No new runtime files, folders, namespaces, dispatcher, registry, or form factory were introduced.

## Canonical replacements
- `add_desc(...)` -> `add_description(...)`
- `add_row(...)` -> `add_field(...)`
- `add_code(...)` -> `add_field(...)`
- `add_pass(...)` -> `add_password(...)`
- `add_itSelector(...)` -> `add_selector(...)`
- `add_select(...)` -> `add_selector(...)`
- `add_itAutoSelect(...)` -> `add_auto(...)`
- `add_itDate(...)` -> `add_date(...)`
- `add_timeSelector(...)` -> `add_time(...)`
- `add_itButton(...)` -> `add_button(...)`

## What changed in `itForm2.class.php`
The synonym public methods were removed after all project call sites were migrated to canonical names. The public canonical methods remain available and keep the same field-building behavior.

## What was intentionally not changed
- submit/event flow
- field XML structure
- form storage format
- editor integration
- button rendering
- existing canonical method names

## Verification target
After applying this patch, project code should no longer call removed synonym methods.
