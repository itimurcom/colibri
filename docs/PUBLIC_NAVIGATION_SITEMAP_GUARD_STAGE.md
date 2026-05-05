# M0 / P81 public navigation sitemap guard stabilization bundle

## Scope

This patch stabilizes the public navigation and sitemap rendering boundary without changing bootstrap order, routing, DB schema, storage format, or public entrypoint names.

Touched runtime zones:

- `public/engine/core/engine_menus.php`
- `public/sitemap.php`
- `SKEL80/classes/system/itSiteMap.class.php`

## Why this patch exists

After the P78 catalog DB-row guards and P80 session/catalog personalization guards, the next weak public boundary was the menu/sitemap layer. It consumes global menu arrays, language/category definitions, and item DB rows directly. If any row is missing, malformed, or partially decoded, the public HTML/XML output can emit warnings or invalid XML.

This patch keeps the legacy structure intact and only adds local guard helpers around reads that were already optional in practice.

## Changes

### Public menu/navigation guards

`engine_menus.php` now uses local helpers for:

- current language/theme fallback reads;
- row value access;
- visibility checks;
- title constant lookup;
- logged-in user checks before adding the cabinet menu node.

The generated menu links and visual structure are preserved. The patch only prevents malformed rows from producing direct array-offset warnings.

### Sitemap guards

`public/sitemap.php` now:

- initializes the page list explicitly;
- resolves the base URL with guarded fallbacks;
- reads language rows from `$lang_cat` when available, falling back to the old `ru/en` behavior;
- skips malformed menu/category rows;
- skips malformed item DB rows;
- skips item rows without localized `url_xml` values;
- handles a non-array/empty `itMySQL::_request(...)` result safely.

### XML output hardening

`itSiteMap` now:

- skips non-array page rows and rows without a valid `url`;
- escapes XML values via `htmlspecialchars(..., ENT_XML1 | ENT_QUOTES, 'UTF-8')`;
- ignores invalid `datetime` values instead of forcing bad `lastmod` output;
- uses `date('Y-m-d', ...)` instead of deprecated `strftime(...)`.

## Preserved behavior

Not changed:

- bootstrap/config/env order;
- routes;
- `.htaccess` behavior;
- DB schema;
- storage format;
- public entrypoint names;
- menu CSS classes and DOM structure;
- sitemap item query semantics;
- public function names.

## Deletions

No files were deleted.

No business logic was removed. The patch only replaces direct fragile reads with guarded local helpers in the same files.

## Suggested runtime checks

When a local vhost is available, check:

- `/`
- `/ua/`
- `/en/`
- `/items/`
- `/sitemap.php`
- `/sitemap.xml` if routed to the PHP sitemap entrypoint
- Apache/PHP logs for `Undefined array key`, `Trying to access array offset on value of type null`, XML warnings, and deprecated `strftime()` output.
