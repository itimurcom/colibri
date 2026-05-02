# M0 / P52 editor events post-consolidation stabilization bundle

## Goal
Stabilize the editor event runtime after the P45/P46 editor consolidation work without touching `itForm2`, editor storage format, or editor block classes.

## Runtime scope
Changed file:
- `SKEL80/events/editor/editor_events.func.php`

## What changed
- Repacked editor creation is centralized in `editor_events_repacked_editor(...)`.
- Store + reload response handling is centralized in `editor_events_store_and_reload(...)`.
- Store + AJAX reload response handling is centralized in `editor_events_store_and_ajax_reload(...)`.
- Repeated editor event cases now use those local event helpers.
- The duplicated unreachable `ed_zoom` switch branch was removed.

## What intentionally did not change
- No `itForm2` changes.
- No `itEditor` storage format changes.
- No editor block class changes.
- No public editor event operation names were changed.
- No new runtime files, folders, controllers, registries, or namespaces were added.

## Manual checks
After applying this patch, test:
- editor state switch view/edit;
- text save;
- add/remove text block;
- field up/down;
- avatar add/remove/switch/zoom;
- media add/change;
- gallery add/remove/up/down/move/text/link.
