# M0 / P91 theme header / schema output guard stabilization bundle

## Scope

This stage stabilizes the public theme/header/schema output boundary after the customer-flow and mailing runtime guard stages.

The patch focuses on warning-prone shell rendering paths that are executed on nearly every public page:

- `SKEL80/classes/system/itHeader.class.php`
- `SKEL80/classes/shop/itMarkOrg.class.php`
- `public/themes/default/tpl.header.php`
- `public/themes/default/tpl.default.php`
- `public/themes/default/tpl.footer.php`

## What changed

- Added guarded global-array reads inside `itHeader::prepare()` for `plug_og`, `plug_css`, `plug_js`, `plug_media`, `plug_meta`, and `plug_skip`.
- Added guarded server reads for header URL/media/CSS paths so missing `HTTP_HOST`, `REQUEST_URI`, or `DOCUMENT_ROOT` no longer leak warnings into HTML.
- Declared and initialized the legacy `itHeader::$css` runtime property to avoid PHP 8.x dynamic-property output.
- Guarded minified JavaScript and CSS inclusion against missing files or failed reads while preserving valid asset output.
- Stabilized theme templates against absent `$_CONTENT[...]` slots and direct `$_REQUEST['controller']` access.
- Stabilized organization schema output by guarding partial `itMarkOrg` options and declaring the legacy `found` property used by JSON-LD rendering.

## Preserved behavior

This patch intentionally does not change:

- bootstrap/config/env behavior;
- routes or public entrypoint names;
- DB schema or storage format;
- theme file names;
- Google Tag Manager markup;
- existing header/meta/schema output semantics for valid payloads.

## Runtime target

The goal is to prevent public page HTML from being polluted by PHP warnings such as:

- undefined `$_CONTENT[...]` keys in theme templates;
- undefined `$_REQUEST['controller']` in the default theme body;
- undefined/malformed `plug_og` or `plug_meta` rows in header generation;
- missing server keys in CLI/smoke-test contexts;
- dynamic `itHeader::$css` or `itMarkOrg::$found` properties under PHP 8.x.

## Verification

Minimum verification after applying the patch:

```bash
php -n -l SKEL80/classes/system/itHeader.class.php
php -n -l SKEL80/classes/shop/itMarkOrg.class.php
php -n -l public/themes/default/tpl.header.php
php -n -l public/themes/default/tpl.default.php
php -n -l public/themes/default/tpl.footer.php
```

Suggested browser checks:

- `/`
- `/en/`
- `/items/`
- `/en/settings/`
- `/mailing/`

Watch Apache/PHP logs for `Undefined array key`, `Undefined variable`, `Creation of dynamic property`, `file_get_contents`, and JSON/HTML pollution warnings.
