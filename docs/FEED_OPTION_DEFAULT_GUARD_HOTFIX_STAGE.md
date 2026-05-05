# M0 / P86 feed option default guard hotfix bundle

## Scope

This hotfix stabilizes the legacy `itFeed` constructor and feed-row assembly paths that can emit PHP warnings into AJAX/JSON responses.

The immediate regression was visible on `/mailing/`: `Undefined array key "mailing_history"` from `SKEL80/classes/blocks/itFeed.class.php` when a feed name was absent from serialized `FEED_LOOP` settings.

## Changes

- Added local `itFeed::option_value()` for legacy `ready_val()`-compatible option reads without undefined-array warnings.
- Added local `itFeed::option_array()` for safe decoding of serialized feed config constants.
- Guarded `FEED_LOOP`, `FEED_START`, and `FEED_NUMBER` lookups by feed name.
- Guarded one-field feed source reads and feed-control wrapping against missing rows/control keys.
- Guarded weighted-feed `show_as` size lookup against incomplete rows.

## Boundaries preserved

- No bootstrap/config/env changes.
- No route changes.
- No DB schema changes.
- No storage format changes.
- No public entrypoint changes.
- No file deletion.

## Manual check targets

- `/mailing/` AJAX feed pagination/filtering.
- Browser console should no longer show `Undefined array key "mailing_history"` before JSON payloads.
- Existing feed buttons should continue to render with the same markup.
