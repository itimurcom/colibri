# M0 / P71 router/language request baseline stabilization bundle

## Goal
Stabilize the old bootstrap routing/language/request baseline without introducing a new dispatcher, framework, route table, namespace, or compatibility layer. This is a guard-pass around the existing `itRouter`, `itLang`, and `itSite` runtime contracts.

## Runtime scope
Changed files:
- `SKEL80/classes/system/itRouter.class.php`
- `SKEL80/classes/system/itLang.class.php`
- `SKEL80/classes/system/itSite.class.php`

## What changed
- Normalized router path parsing so language and controller detection works the same with or without a trailing slash.
- Kept existing route semantics for language-prefixed URLs, non-language URLs, numeric views promoted to `rec_id`, and MAC-like views promoted to `rec_id`.
- Added request-key guards for `controller`, `view`, `table_name`, `rec_id`, and `lang` in `itSite` so direct reads of optional `$_REQUEST[...]` keys do not produce runtime warnings.
- Added safe language lookup helpers in `itLang` so invalid/stale session language values fall back to the configured/default allowed language instead of reading missing `$lang_cat[...]` keys.
- Stabilized language switch link generation so URLs without an existing language prefix are generated with the requested language prefix instead of returning the unchanged URL.
- Guarded language selector rendering and alternate-language lookup against incomplete or missing language rows.

## Preserved behavior
- No controller files are moved or renamed.
- No route table is introduced.
- No new runtime files are introduced.
- Existing public class names and public method names are preserved.
- Existing constants and language files remain the source of truth.
- Existing DB schema, serialized storage fields, editor/form/feed internals, and URL endpoint names are not changed.
- No files are deleted.

## Manual checks after applying
Prioritize URL and language regression checks:
- `/`, `/en/`, `/ua/`, `/ru/`.
- `/en/items/` and `/en/items`.
- `/items/123/` and `/items/123`.
- `/en/items/123/` and `/en/items/123`.
- Language switch buttons from root, catalog, item page, contacts, order, and cabinet pages.
- 404 fallback for an unknown controller.
- Admin/settings and cabinet pages after login.

## Next recommended step
After this patch, the M0 runtime cleanup should pause for manual smoke checks on routing/language, wizard/object, catalog, wishlist, and form-page flows. If there are no runtime errors, the next useful patch is a final M0 audit/backlog/handoff bundle rather than another aggressive cleanup pass.
