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
Large shared form runtime with many repetitive field builder methods. Current cleanup direction: reduce repeated field-definition builders and stored-form mutation wrappers in place.
