# M0 / P50 itMySQL strict-mode compatibility cleanup bundle

## Goal

Reduce duplicated legacy SQL value preparation in `SKEL80/classes/system/itMySQL.class.php` without changing the public API, query entrypoints, or storage format.

## Runtime scope

Changed file:
- `SKEL80/classes/system/itMySQL.class.php`

## What changed

The repeated SQL value preparation logic is now centralized in local helpers:

- `itMySQL::prepare_sql_value(...)`
- `itMySQL::prepare_update_pair(...)`

These helpers are now used by:

- `update_value_db(...)`
- `update_db_rec(...)`
- `prepare_values(...)`

## Why this matters

Before this patch, legacy write paths prepared SQL values in several places with similar but duplicated array/NULL/string handling. That made strict-mode compatibility harder to reason about and increased the risk that future fixes would update one path but miss another.

## Preserved behavior

- Public method names are unchanged.
- Static wrapper methods are unchanged.
- Empty arrays still become SQL `NULL`, as before.
- Non-NULL scalar values are still written as quoted strings.
- Existing JSON storage format is unchanged.
- No DB schema or SQL mode changes were introduced.

## What was not changed

- No query builder was added.
- No PDO layer was introduced.
- No mysqli connection behavior was changed.
- No table/column metadata introspection was added.
- No files were moved or deleted.

## Manual checks

After applying:

- save editor text;
- save a form field;
- add/edit an item;
- check PHP logs for `itMySQL.class.php` warnings;
- check DB values where empty strings and empty arrays are expected.
