# M0 / P92 public UI helper request guard stabilization bundle

## Scope

This stage stabilizes legacy public UI helper boundaries that still rendered directly from `$_REQUEST`, `$_SERVER`, global menu arrays, slider rows, or autocomplete payloads.

The patch is intentionally limited to runtime guard/stabilization work. It does not change bootstrap/config/env behavior, routes, DB schema, storage format, or public entrypoint names.

## Changed areas

- `public/engine/core/engine_autoselect.php`
  - added guarded autocomplete request reads for `term`;
  - normalized the autocomplete term before using it in search helpers;
  - preserved legacy result shape while skipping malformed DB rows;
  - guarded missing `id`, `relev`, and image payload fields in autocomplete results.

- `public/engine/core/engine_languages.php`
  - guarded language-row rendering against incomplete language rows;
  - normalized the animation delay counter;
  - left the old iPhone language menu block commented, but updated the request read inside it so future restoration does not reintroduce `$_REQUEST['lang']` warnings.

- `public/engine/core/engine_arrays.php`
  - normalized wishlist session storage to an array;
  - guarded admin/user runtime checks before reading prepared global arrays;
  - guarded content/category/language/status/group/sex/item prepared-array generation against missing rows and fields;
  - replaced direct `$_REQUEST['view']` / `$_REQUEST['rec_id']` reads with guarded values.

- `SKEL80/classes/blocks/itMenu.class.php`
  - normalized constructor options and menu data arrays;
  - guarded current-view request lookup;
  - fixed the old fallback-link bug where menu rows could read an undefined `$link` variable;
  - guarded row flags/title/controller/link and menu color configuration for both top and mobile menus.

- `SKEL80/classes/images/itSlider.class.php`
  - guarded constructor options, user-runtime access, DB result rows, and `slide` request lookup;
  - normalized `ed_rec` to an array before `count()` / `foreach()`;
  - guarded missing slider `title_xml`, `href_xml`, and `avatar` fields;
  - protected `set_title()` / `set_href()` from empty DB rows.

- `SKEL80/classes/blocks/itSharer.class.php`
  - guarded `HTTP_HOST` and `REQUEST_URI` fallback URL generation;
  - guarded social/share arrays before rendering links;
  - initialized local result variables to avoid warning output.

- `SKEL80/classes/editor/itCats.class.php`
  - guarded category-title list rendering against non-array DB results and malformed rows;
  - replaced direct `$_REQUEST['rec_id']` selection logic with a guarded request value;
  - protected adjacent-row lookups and category name/remove helpers from empty rows.

## Explicit non-goals

- no bootstrap/config/env changes;
- no route or `.htaccess` changes;
- no DB schema changes;
- no storage format changes;
- no public entrypoint renaming;
- no file deletions.

## Suggested smoke checks

- open a normal public page with top/mobile menu;
- open a language-prefixed page such as `/en/`;
- test item autocomplete/search input with an empty and non-empty term;
- open a page that renders the main slider;
- open a page with social share/group icons;
- open an editor/material category list page while logged in.
