# Shared / Project boundaries

## Shared platform core
**Root:** `SKEL80/`

Owns:
- bootstrap lifecycle
- shared class library
- event discovery
- cross-project defaults
- common primitives used by all projects

Must not own:
- Colibri-specific business rules
- Colibri theme decisions
- project-only delivery behavior

## Project bootstrap surface
**Roots:** `public/index.php`, `public/config*.php`

Owns:
- entrypoint into the deployable project
- config layering
- env/secrets overlays
- public runtime toggles for the project

## Project overlay
**Root:** `public/engine/`

Owns:
- project constants and ini precedence
- project engine fragments
- project classes/events/units
- late customs
- declared extension points over shared kernel

## Project delivery surface
**Roots:** `public/mvc/`, `public/themes/`, `public/languages/`

Owns:
- request preprocessors/controllers
- responder/view assembly
- theme rendering
- localization resources

## Mixed hotspots
These zones are still transitional and intentionally marked as such:
- `SKEL80/kernel/engine_functions.php`
- `public/ed_field.php`
- `public/more.php`
- `public/mvc/controllers/`
- `public/mvc/views/`
- `public/engine/kernel.customs.php`

The rule for mixed hotspots is simple:
- do not expand them further
- use them only when the old runtime contract still requires them
- prefer pushing new work into clearly owned zones
