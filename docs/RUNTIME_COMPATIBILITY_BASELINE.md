# M0 / P2 runtime compatibility baseline

## Goal

Keep the historical `SKEL80/` shared-core + `public/` project-overlay architecture unchanged, while making the runtime predictable on modern PHP.

## Code changes in this patch

### 1. Runtime settings and logging
- Added `SKEL80/kernel/runtime_compat.php`.
- `SKEL80/run.php` now applies runtime settings after config resolution and installs logging/error handlers.
- Added baseline logging target: `public/logs/php-runtime.log`.

### 2. Config and secrets hardening
- `public/config.php` now supports:
  - base defaults in the tracked file,
  - optional ignored overlays from `public/config.secrets.php`,
  - optional ignored overlays from `public/config.secrets.local.php`,
  - environment-variable overrides.
- Added `public/config.secrets.example.php` as a placeholder template.

### 3. Short open tags
- Replaced legacy `<?` with `<?php` across runtime PHP files touched by the current tree.
- This removes dependence on `short_open_tag` for the project runtime.

### 4. Deprecated `each()` removal
- Replaced `each()` loops in the bundled legacy PHPMailer sources with `foreach` equivalents.
- This keeps the existing mailer stack but removes a direct PHP 8 incompatibility.

### 5. Mailer baseline hardening
- `SKEL80/classes/mailer/itMailer.class.php` now defaults SMTP debug output to `error_log` and keeps debug level configurable via `DEFAULT_SMTP_DEBUG`.
- Sender and SMTP credentials are normalized more defensively when project settings are incomplete.

### 6. Include/bootstrap normalization
- `public/engine/kernel.php` now loads `SKEL80/run.php` via an absolute path derived from `__DIR__` instead of a fragile relative include string.
- Runtime error policy is no longer hard-coded there.

## Intentionally not done in this patch
- no behavioral rewrite of the shared kernel
- no mail transport replacement
- no controller/view renaming campaign
- no SQL layer modernization
- no presentation cleanup beyond runtime compatibility needs

## Expected result

The platform keeps its original authorial model:
- shared `SKEL80` core,
- project overlay in `public/`,
- ordered bootstrap phases,
- function-first extension.

At the same time, the runtime becomes easier to run and debug on modern PHP without relying on hidden server settings like `short_open_tag` or deprecated `each()` support.

## Startup deprecation hotfix follow-up

The runtime startup hotfix bundle now also addresses the first PHP 8.2+ deprecation wave seen during real Colibri boot:

- declared legacy runtime properties explicitly in `itRouter`, `itSettings`, `itUser` and `itFocus` instead of relying on dynamic property creation;
- preserved both `user_id` and `id_of_user` on `itSettings` so the historical settings model keeps its current semantics while stopping the PHP deprecation noise;
- normalized DB value decoding in `itMySQL` so `NULL` no longer reaches `html_entity_decode()` and triggers startup warnings.

Expected result: the repeated startup deprecations reported from `itRouter`, `itSettings`, `itUser`, `itFocus` and `itMySQL` should disappear without changing the shared-kernel + project-overlay architecture.


## M0 / P6 feed and JSON deprecation bundle
- `SKEL80/classes/blocks/itFeed.class.php`
  - declared explicit legacy runtime properties used during feed assembly to avoid PHP 8.2+ dynamic property deprecations
  - added NULL-safe JSON handling for one-field feed decoding
- `SKEL80/kernel/events/cheking/isJson.func.php`
  - made `isJson()` NULL-safe and empty-string-safe before `json_decode()`
- `SKEL80/classes/system/itMySQL.class.php`
  - added centralized NULL-safe JSON decoding helper for DB result normalization

## M0 / P9 mail forms dynamic-properties hotfix
- `SKEL80/classes/forms/itModal.class.php`: declared `ajax`, initialized `fields`, fixed `set_animation()` assignment.
- `SKEL80/classes/forms/itButton.class.php`: declared `ajax`.
- `SKEL80/classes/f2/itForm2.class.php`: declared `error`, `class`, `field`, `column`, `accepted`, `code`, `button_xml`, and normalized internal writes to `buttons_xml`.
