# M0 / P23 ed_field duplicate branch reduction stage

## Goal
Reduce direct duplication inside `public/ed_field.php` without adding a second runtime layer, new directories, or a mini-framework around legacy `op` handling.

## Runtime scope
Changed file:
- `public/ed_field.php`

## What changed
- Added three local helpers only:
  - `ed_field_json_result(...)`
  - `ed_field_redirect_result(...)`
  - `ed_field_update_value_and_redirect(...)`
- Replaced repeated `json_encode(['result' => 1, ...])` tails with a single local JSON helper.
- Replaced repeated `update -> redirect` tails with a single local helper.
- Collapsed four mail-status dispatch branches into one local `in_array(...)` gate.
- Collapsed four editor flag-toggle branches (`is_new`, `is_econom`, `is_shop`, `is_replicant`) into one local gate.

## What was removed
- Empty no-op block in `ajaxpin`:
  - `if ($pinned) { }`
- Two unused upload counters:
  - `$count=0` in `banner`
  - `$count=0` in `add_slider`

These removals do not change runtime behavior.

## What intentionally did not change
- No namespace migration.
- No controller/dispatcher/registry layer.
- No new runtime files.
- No operation (`op`) rename.
- No normalization of historical `add_content` / `die;` behavior in this stage.

## Result
The file stays a legacy mutation hub, but duplicate success tails and duplicate update+redirect branches are materially reduced in-place.
