# M0 / P24 ed_field admin and upload duplicate reduction

## Scope
- `public/ed_field.php`
- `docs/BOUNDARY_QUICKSTART.md`
- `docs/LEGACY_MIXED_HOTZONES.md`
- `docs/ED_FIELD_ADMIN_UPLOAD_DUPLICATE_REDUCTION_STAGE.md`

## Goal
Continue direct cleanup of `public/ed_field.php` without adding new runtime files, new layers or a secondary action framework.

## What changed
- Added `ed_field_uploaded_files()` to collapse duplicated upload loops used by `banner` and `add_slider`.
- Added `ed_field_slider_url()` to collapse duplicated `slider_title` / `slider_href` redirect logic.
- Collapsed repeated `user_name` / `user_phone` / `user_email` branches into one grouped branch with the same `html2txt($_REQUEST['value'])` normalization.
- Reduced branch noise and repeated braces/temporary variables while keeping the same entrypoint and the same `op` names.

## What intentionally did not change
- No namespace.
- No dispatcher.
- No controller / registry / command bus.
- No new runtime directories.
- No behavior rewrite of `add_content`; historical `die;` was preserved.

## Result
`public/ed_field.php` became shorter and simpler to scan in-place, while preserving the same operation surface.
