# M0 / P87 mailing history template guard stabilization bundle

## Scope

This stage stabilizes the legacy mail history and mail-template runtime boundary after the feed option hotfix.

The patch intentionally stays inside the existing legacy files and does not change routing, bootstrap order, DB schema, storage format, or public entrypoint names.

## Files changed

- `public/engine/core/engine_mails.php`
- `public/ed_field.php`
- `SKEL80/classes/mailer/itMailTemplate.class.php`
- `PROJECT_PATCHLOG.md`
- `docs/MAILING_HISTORY_TEMPLATE_GUARD_STAGE.md`

## Runtime issues addressed

- Mail history rows are now rendered through guarded row reads for `id`, `datetime`, `reply`, `message`, `subject`, `to`, `code`, and `status`.
- Missing or unknown mail statuses now fall back to a safe `ERROR` metadata block instead of reading absent `$mailers[...]` keys.
- Measurement subject color rendering now uses a local fallback helper instead of assuming every measurement form metadata row exists.
- Mail preview links now skip invalid mail rows instead of generating warnings from missing row fields.
- Mail status actions in `ed_field.php` now guard missing mail rows and missing `reply` fields; spam/unspam falls back to the current mail id when no reply is available.
- `itMailTemplate` now guards missing `prepared`, `result`, `tpl`, `table_name`, `code`, and `subject` fields so mail rendering does not leak warnings into HTML/AJAX responses.

## Boundaries preserved

- No bootstrap/config/env changes.
- No route changes.
- No DB schema changes.
- No storage format changes.
- No public entrypoint changes.
- No file deletion.
- Existing mail status operation names are preserved: `spam`, `spam_x`, `mail_x`, `mail_not_x`.

## Manual checks recommended

- Open `/en/mailing/` and switch all mail-history tabs.
- Trigger feed pagination/more loading in mail history.
- Open a mail preview modal.
- Toggle spam/unspam and delete/restore actions on a mail row.
- Submit one test contact/order/measurement form and confirm the mail template renders without PHP warnings.
