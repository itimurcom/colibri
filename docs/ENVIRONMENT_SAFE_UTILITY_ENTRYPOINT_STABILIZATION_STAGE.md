# M0 / P73R environment-safe utility entrypoint stabilization bundle

## Scope

This patch replaces the rejected P73 direction with a safer public utility-entrypoint pass.

Changed files:

- `public/img.php`
- `public/more.php`
- `public/login.php`
- `public/mailbody.php`
- `public/maillogo.php`
- `public/soclogin.php`
- `PROJECT_PATCHLOG.md`
- `docs/ENVIRONMENT_SAFE_UTILITY_ENTRYPOINT_STABILIZATION_STAGE.md`

The patch intentionally does not modify `public/config.php`, `public/index.php`, `SKEL80/run.php`, Apache rules, route definitions, DB schema, storage format, or bootstrap include order.

## What changed

### `public/img.php`

- Removed the old debug abort that printed `PICTURE_ROOT` and stopped execution before image handling.
- Added local request/path guards for `img_url`.
- Rejects empty, absolute, NUL-containing, or parent-directory image paths before touching the filesystem.
- Keeps the old fallback behavior: when a generated image is missing, it asks the existing thumbnail/avatar pipeline to generate the matching image.
- Returns a safe `404` when the image path cannot be resolved instead of continuing into image output with an invalid path.

### `public/more.php`

- Added a local request getter for optional `op` and `data` payloads.
- Kept existing named operations: `as_main`, `as_item`, `user`, `contents`.
- Kept existing encrypted feed payload rendering path.
- Missing feed `data` now returns the existing structured invalid-payload JSON response without reading an undefined request key.

### `public/login.php`

- Added local request/session getters for the login entrypoint only.
- Guarded optional `user_login`, `user_password`, `url`, and reCAPTCHA score reads.
- Fixed the old `v3cheked` typo in the error-message path by reusing the guarded `v3checked.score` value.
- Preserved the existing login redirect flow and default login-page behavior.

### `public/mailbody.php`

- Reads `id` through a guarded integer value.
- Keeps the existing requirement that the user must be logged in before viewing a mail body.
- Does not change mail storage, rendering, permissions, or DB helpers.

### `public/maillogo.php`

- Reads `mail_id` through a local request getter.
- Keeps the existing tracking behavior for encrypted mail IDs.
- Adds a fallback for missing `DOCUMENT_ROOT` without changing normal web-server behavior.
- Returns `404` if the logo file cannot be found instead of passing an invalid path into the image output helper.

### `public/soclogin.php`

- Reads optional `op` through a local request getter.
- Preserves the existing OAuth and post-login redirect behavior.

## Explicitly not changed

- No new framework or dispatcher.
- No namespace migration.
- No new files in runtime code.
- No route changes.
- No Apache or `.htaccess` changes.
- No config/env/bootstrap changes.
- No DB schema changes.
- No storage-format changes.
- No file deletion.

## Removal note

One executable debug line was removed from `public/img.php`:

- `echo PICTURE_ROOT; die;`

Reason: it prevented the image entrypoint from doing its real work and matched the P73R goal of checking `public/img.php` without fatal/debug aborts. This was not application logic; it was an old debug stop before the existing image-resolution path.

## Suggested manual verification

1. Open `/img.php?img_url=<known-generated-image>` and confirm it returns the image rather than printing a path.
2. Open `/img.php` without `img_url` and confirm it returns `404` without PHP warnings.
3. Trigger `/more.php` with a valid feed payload and confirm existing pagination/feed rendering still works.
4. Trigger `/more.php` without `data` and confirm it returns invalid-payload JSON instead of warnings.
5. Submit login with valid credentials.
6. Submit login with an empty login or wrong password and confirm focus/error behavior still works.
7. Open `/mailbody.php?id=<known-mail-id>` while logged in.
8. Request `/maillogo.php` with and without `mail_id` and confirm the logo is served.
9. Trigger social login flow and confirm the OAuth branch still starts when `op=login`.
10. Check PHP/Apache logs for `Undefined array key`, `Undefined variable`, `Undefined constant`, `failed to open stream`, and `Cannot redeclare` in these entrypoints.

## Next step

After this patch, the safest next M0 step is either manual smoke testing of the utility entrypoints or a bounded audit of read-only public diagnostic/dev scripts such as `free.php`, `setip.php`, and legacy one-off helpers. Do not return to the rejected P73 implementation and do not change bootstrap/env order unless a concrete runtime failure points to a specific line.
