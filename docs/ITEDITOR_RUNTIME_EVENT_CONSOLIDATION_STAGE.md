# M0 / P46 itEditor runtime event consolidation bundle

## Goal
Continue editor runtime simplification as a bundle, not as a micro cleanup.

## Runtime scope
Changed files:
- `SKEL80/classes/editor/itEditor.class.php`
- `SKEL80/events/editor/editor_events.func.php`

## What changed in `itEditor.class.php`
- Editor compile flow now reuses local helpers for related-content rendering, html cache clearing, store-data normalization and AJAX container payload creation.
- `compile()` uses the existing editor field bootstrap helper instead of keeping a separate first-field initialization branch.
- `store()` now delegates cache clearing and strict DB compatibility normalization to local helpers.
- `container()` now delegates event payload and view/edit content selection to local helpers.
- Gallery link/text writes now share one local localized-gallery setter.
- `count_media()` and `_redata()` were simplified without changing their public signatures.

## What changed in `editor_events.func.php`
- Added local JSON response helpers for editor event replies.
- Replaced repeated JSON reload / AJAX reload payload construction with shared local helpers.
- Removed generated CRC metadata and dead commented-out redirect/return leftovers.
- Removed the duplicate unreachable `ed_zoom` redirect branch.

## What stayed unchanged
- No public editor method names were removed.
- No editor storage format changes.
- No `itForm2` changes.
- No new runtime files, folders, namespace, registry, dispatcher or action framework.
- No file deletion; remove manifest is not required.

## Manual regression checks
After applying:
- open a content/item page in public view;
- open the same page as editor/admin;
- switch editor view/edit state;
- edit text block;
- add/remove/move gallery images;
- edit gallery text/link;
- reload async editor container.
