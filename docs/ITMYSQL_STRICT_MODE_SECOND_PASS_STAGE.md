# M0 / P55 itMySQL strict-mode second pass bundle

## Goal
Continue DB/strict-mode cleanup in `SKEL80/classes/system/itMySQL.class.php` without adding a new DB layer, PDO, query builder, or changing public API.

## Runtime file
- `SKEL80/classes/system/itMySQL.class.php`

## What changed
- DB read-row normalization is centralized in `normalize_db_row(...)`.
- `get_rec_from_db(...)`, `get_arr_from_db(...)`, and `request(...)` now use the same row normalization path.
- Static wrapper constructor casing was normalized from legacy typo variants like `itMysQL` / `itMysql` to `itMySQL`.

## Why this matters
Before this patch, several read paths decoded database values through separate loops. That made strict-mode/null/json compatibility harder to reason about and easier to regress. The class now has one read-row normalization path and one value normalization path.

## Preserved behavior
- public method names are unchanged;
- public signatures are unchanged;
- mysqli connection behavior is unchanged;
- SQL query strings and storage format are unchanged;
- no PDO/query-builder layer was added;
- write-side preparation from P50 remains unchanged.

## Manual checks
After applying:
- open pages that use `itMySQL::_get_rec_from_db(...)`;
- open catalog/feed pages that use `itMySQL::_request(...)`;
- save a content/editor block;
- save an item field;
- check PHP and MySQL logs for NULL/json/strict-mode warnings.
