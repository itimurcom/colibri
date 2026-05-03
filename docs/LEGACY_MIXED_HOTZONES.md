# Legacy mixed hotzones

These locations are intentionally called out because they cross more than one responsibility boundary.

## 1. `SKEL80/kernel/engine_functions.php`
Shared helper layer that also contains formatting and delivery-adjacent helpers.
Current cleanup direction: collapse repeated environment/request helper logic in place before attempting any wider extraction.

## 2. `public/ed_field.php`
Legacy mutation endpoint mixing forms, editor transport, auth and business operations.
Current cleanup direction: reduce direct duplicate branches in place first, then regroup remaining lanes without adding dispatcher/controller layers. See `docs/ED_FIELD_DUPLICATE_BRANCH_REDUCTION_STAGE.md`.

## 3. `public/more.php`
Legacy feed endpoint mixing SQL-backed feed assembly and HTML delivery.

## 4. `public/mvc/controllers/`
Historical preprocessors that still carry delivery decisions.

## 5. `public/mvc/views/`
Historical responders that still prepare data and render markup together.

## 6. `public/engine/kernel.customs.php`
Late project hook that can cross into runtime, overlay and delivery state.

## Rule going forward
- Mixed zones are allowed to remain while needed.
- New behavior should prefer clearly owned zones.
- Extraction should happen slice by slice, not via blind rewrite.

Runtime host links: prefer runtime base-url constants from `public/config.php` over hardcoded `atelier-colibri.com` references in mail, items, user PIN and settings flows.

## 7. `public/engine/core/units/items/engine_items.php`
Primary item read/render hotspot. Current cleanup direction: collapse duplicated block/query/link/markup assembly in place before any broader slice extraction.

## 8. `SKEL80/classes/blocks/itFeed.class.php`
Shared feed runtime that still mixes payload building, control rendering and HTML assembly. Current cleanup direction: reduce more/fewer button and feed-row compilation duplication in place.

## 9. `SKEL80/classes/f2/itForm2.class.php`
Large shared form runtime with many repetitive field builder methods. Current cleanup direction: reduce repeated field-definition builders in place, preserve public `add_*` method names, and avoid new form factory/controller layers.

## Stabilization checkpoint: generated endpoints
`public/.htaccess` routes `robots.txt` and `sitemap.xml` before broad route rewrites, so generated endpoint behavior stays explicit after cleanup and hotfix patches.

## M0 / P34 engine_functions second cleanup pass
`SKEL80/kernel/engine_functions.php` now uses local shared primitives for repeated random-string and localized-date formatting logic. This is still direct cleanup inside the shared helper dump, not a new runtime layer.

## itForm2 canonical method names
`itForm2` no longer keeps public synonym add-methods for old naming variants. Project call sites should use the canonical method names documented in `docs/ITFORM2_CANONICAL_METHOD_NAMES_STAGE.md`.

## P37 note
`itForm2.class.php` canonical method migration remains active; P37 only fixes field-builder regressions for form-editor field creation and list-field metadata.

M0 / P39 note: `itFeed.class.php` had only visual separator/CRC cleanup; `engine_items.php` had only unused-local and dead-ternary cleanup.

## M0 / P40 engine_functions third cleanup pass
`SKEL80/kernel/engine_functions.php` now reuses local shared primitives for hash generation and JSON output, and stale CRC/decorative separator comments were removed. This remains direct cleanup inside the existing shared helper file.

## Project-wide comment noise cleanup
Generated CRC metadata blocks and decorative separator comments are considered visual legacy noise. They may be removed in broad cleanup patches when no runtime code is changed.

## M0 / P49 `itForm2.class.php`
P49 consolidates duplicated field runtime rendering inside `itForm2.class.php` while preserving public method names and form field storage. The form editor event files were intentionally left untouched in this patch.

## M0 / P52 `editor_events.func.php`
P52 stabilizes repeated editor event runtime paths after the P45/P46 editor work. The patch keeps operation names and storage format unchanged while removing duplicated editor creation/store/reload code and the unreachable duplicate `ed_zoom` branch.

## M0 / P55 `itMySQL.class.php`
The second strict-mode pass centralizes DB read-row normalization and normalizes legacy static wrapper constructor casing. No new DB layer, PDO migration, query builder, or public API change was introduced.

## M0 / P56 runtime hotzone audit and stabilization
P56 fixes the editor-events repacked-editor recursion regression and adds narrow guards in f2 list settings and DB value preparation. This is a stabilization pass after P49–P55, not a new architecture step.

## M0 / P57 catalog/feed second pass
Catalog/feed remains an active runtime hotspot. P57 keeps the existing `itFeed` and item-card API, but reduces per-row callback lookup and adds guards around invalid request sources and request-driven item-card animation.

## M0 / P58 editor media/gallery second pass
Editor gallery upload and gallery move/delete metadata handling are still legacy-hotzone code. P58 consolidates repeated gallery event handling and adds guards around partial gallery `text/link/value` metadata without changing public editor API.

## M0 / P59 customer/auth runtime cleanup
`engine_customer_events.php` and `engine_customers.php` were consolidated locally around repeated customer form/modal/auth helper logic. Existing public functions and route/session behavior remain the boundary.
