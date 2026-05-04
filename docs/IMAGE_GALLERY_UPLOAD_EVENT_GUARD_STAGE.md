# M0 / P76 image gallery upload event guard stabilization bundle

## Goal
Stabilize legacy image/gallery upload event handlers after the P73R-P75 request guard chain without changing bootstrap, routes, storage format, database schema, or editor UI contracts.

## Runtime scope
Changed files:
- `SKEL80/events/forms/upload_gal_events.func.php`
- `SKEL80/events/forms/get_form_gallery.func.php`
- `SKEL80/events/images/itimages_events.func.php`
- `SKEL80/classes/images/itImages.class.php`

## What changed
- Added guarded request/file reads for form gallery uploads before reading `op`, `rel`, upload field names, and temporary upload paths.
- Fixed the old `redy_val(...)` typo in `get_form_gallery(...)` by replacing the fragile row/request reads with local guarded helpers.
- Added fallback handling for missing gallery row metadata so optional `name`, `id`, `code`, `element_id`, and `field` values do not emit PHP warnings before HTML/AJAX output.
- Added guarded image event payload normalization for `itImages` AJAX operations before building/reordering gallery storage.
- Declared existing runtime properties in `itImages` to reduce PHP 8.x dynamic-property deprecation output in AJAX responses.
- Hardened `itImages` storage initialization and reorder operations against missing/non-array storage and out-of-range gallery indexes.

## Small code removals
- Removed unused local variables from upload handlers:
  - `$value = NULL` in the form gallery upload path;
  - `$count=0`, `$count++`, and `unset($value)` in the image upload path.
- These variables did not affect upload storage, response payloads, DB writes, or UI flow.

## Preserved behavior
- Public routes and entrypoint names are unchanged.
- Bootstrap/config/env order is unchanged.
- Upload storage format is unchanged.
- DB schema is unchanged.
- Existing operation names are unchanged: `upload_gal`, `itimagesstate`, `itimagesreload`, `itimages_add`, `itimage_x`, `itimage_up`, `itimage_down`, `itimage_n`.
- Existing JSON response shapes are preserved.
- No files were deleted.

## Manual checks
After applying, check:
- upload images in a form gallery field;
- add an image to an `itImages` gallery/slider field;
- delete an image;
- move image up/down;
- move image by number;
- inspect AJAX responses for PHP warnings/deprecations mixed into JSON.
