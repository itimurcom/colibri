# M0 / P66 controller entrypoint stabilization bundle

## Goal
Clean up remaining project-side controller entrypoints after recent controller patches, without changing routes, form operation names, shared runtime, storage format, or introducing a new controller layer.

## Runtime scope
Changed files:
- `public/mvc/controllers/controller.test.php`
- `public/mvc/controllers/controller.items.php`
- `public/mvc/controllers/controller.mailing.php`
- `public/mvc/controllers/controller.settings.php`

## What changed

### `controller.test.php`
- Removed large historical commented experiments and debug snippets.
- Kept the actual current login/test entrypoint behavior.
- Added explicit `$login = false` before passing it by reference to `customer_ajaxlogin_event(...)`.

### `controller.items.php`
- Added local helpers for view/rec-id access, item redirect, item page content, and feed content.
- Added request guards around optional `view`, `rec_id`, and URL-driven item routing.
- Removed stale commented category/debug fragments.

### `controller.mailing.php`
- Removed commented wrapper fragments around `mailing_history_panel()`.
- Kept mailer force-run and history panel behavior.

### `controller.settings.php`
- Split page rendering into local left/right/backup panel helpers.
- Preserved settings, language, SMTP, password, measurement, social and backup panels.
- Updated visible static Ukrainian labels in this controller while keeping the same runtime calls.

## What intentionally did not change
- No routes were changed.
- No submit operation names were changed.
- No shared-core files were changed.
- No `itForm2`, editor, feed or DB runtime files were changed.
- No new runtime files, controllers, dispatcher or framework layer were added.
- No files were deleted; no remove manifest is required.

## Manual checks
After applying:
- `/test/` for guest login panel;
- `/items/`, category catalog pages, and direct item page;
- `/mailing/` admin history page;
- `/settings/` admin settings page.
