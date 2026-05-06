# M0 / P96 runtime warning sweep / legacy boundary stabilization bundle

## Scope

This stage continues the late M0 runtime-stabilization line after P95 with a broader but bounded sweep across legacy value-control classes, diagnostic utility entrypoints, image resizing, request URL generation, language completeness tooling, SMTP default initialization, and the session-backed memcache fallback.

## Why this exists

Several older helpers still assumed complete constructor option arrays, initialized request/server/session globals, valid image metadata, and an initialized user/session runtime. Under PHP 8.x those assumptions can produce `Undefined array key`, `Trying to access array offset on value of type null`, invalid scalar conversion warnings, or fatal follow-up errors from incomplete image/request state.

## Changed areas

- `SKEL80/classes/forms/itInput.class.php`
- `SKEL80/classes/forms/itArea.class.php`
- `SKEL80/classes/forms/itUpGal.class.php`
  - normalize constructor `$options` before reading option keys;
  - guard request fallback values;
  - normalize scalar/array values before `stripslashes()` / `htmlentities()` / gallery CSV parsing.

- `SKEL80/classes/f2/itInput2.class.php`
- `SKEL80/classes/f2/itArea2.class.php`
- `SKEL80/classes/f2/itSelect2.class.php`
- `SKEL80/classes/f2/itSet2.class.php`
- `SKEL80/classes/f2/itUpGal2.class.php`
  - normalize constructor `$options` and nested class option payloads;
  - guard optional request fallbacks such as `multi` and generated checkbox request keys;
  - guard malformed select/set rows before reading configured title/value/color keys;
  - guard missing user runtime before rendering the empty admin select fallback.

- `SKEL80/classes/system/itMemCache.class.php`
  - normalizes session fallback storage before use;
  - guards user id lookup when `$_USER` is absent or incomplete;
  - keeps existing memcached-first behavior and session fallback keys.

- `SKEL80/classes/images/itResizer.class.php`
  - stops resize processing early when image metadata or image resource creation fails;
  - guards EXIF and logo path reads;
  - avoids relying on `$_SERVER['DOCUMENT_ROOT']` when it is not initialized.

- `public/free.php`
  - guards `DOCUMENT_ROOT`, session counters, missing folders, and non-numeric disk-space results in the legacy diagnostic utility.

- `public/languages/cplang.php`
  - guards `DOCUMENT_ROOT`, `glob()`, `file()`, language/color arrays, and missing result arrays in the language completeness utility.

- `SKEL80/kernel/events/browser/get_request_url.func.php`
  - guards missing `HTTP_HOST` and `REQUEST_URI` server values.

- `public/engine/kernel.customs.php`
  - guards missing `SERVER_NAME` before composing the default SMTP user.

## Boundaries kept

This patch does not change:

- bootstrap/config/env behavior;
- routes;
- `.htaccess`;
- DB schema;
- storage format;
- public entrypoint names;
- WYSIWYG editor behavior;
- FORM editor storage semantics;
- URL/language-prefix routing.

## Verification

Minimum verification performed for this bundle:

- `php -n -l` for all changed PHP files;
- `unzip -t` for the generated patch archive;
- checked archive layout so the ZIP contains project-relative paths only and no extra root folder.

Suggested runtime smoke tests after applying:

- open a public page with a form and submit a normal contact/order/measurement flow;
- open admin/form editor controls that render input, textarea, select, set, and gallery fields;
- upload or regenerate a gallery/image thumbnail path;
- open `/languages/cplang.php` and `/free.php` only if those legacy utility pages are still used locally;
- confirm PHP logs stay clean from `Undefined array key`, `Trying to access array offset on value of type null`, and scalar conversion warnings in the touched files.
