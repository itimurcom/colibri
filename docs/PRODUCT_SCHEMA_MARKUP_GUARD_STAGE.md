# M0 / P94 product schema markup guard stabilization bundle

## Scope

This patch stabilizes the legacy product structured-data renderer without changing routing, storage format, public entrypoints, or schema semantics.

## Changed runtime boundary

- `SKEL80/classes/shop/itMarkUp.class.php`

## Runtime fixes

- Normalized constructor `$options` to an array before reading markup keys.
- Normalized global `$_MARKUP` to an array before reading product metadata.
- Replaced direct `ready_val($_MARKUP[...])` reads with guarded by-value lookups.
- Replaced the direct `$_REQUEST['rec_id']` SKU fallback with a guarded request lookup.
- Guarded nested `review[count/value/author]` values for partial product markup payloads.
- Normalized product image payloads so indexes `0`, `1`, and `2` always exist before JSON-LD/RDFA/microdata output.
- Initialized `$_LDJSON`, `$_RDFA`, and `$_SCHEMA` as strings before appending structured-data markup.

## Preserved behavior

- Existing `itMarkUp` class name and constructor remain unchanged.
- Existing product JSON-LD/RDFA/microdata output blocks remain in place.
- Existing fallback values for brand, price, currency, condition, availability, seller, review count/value/author remain unchanged.
- Existing duplicate brand assignment was intentionally preserved to avoid behavior drift in this stabilization stage.

## Not changed

- No bootstrap/config/env changes.
- No route or `.htaccess` changes.
- No DB schema or storage format changes.
- No public entrypoint changes.
- No file deletions.
