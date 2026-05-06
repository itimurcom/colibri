# M0 / P93 mailing queue send guard stabilization bundle

## Scope

This patch stabilizes the legacy mail queue creation and sender runtime boundary after the mailing-history/feed fixes in P86 and P87.

Touched files:

- `SKEL80/classes/mailer/itMail.class.php`
- `SKEL80/classes/mailer/itMailer.class.php`
- `SKEL80/classes/mailer/itMailings.class.php`
- `PROJECT_PATCHLOG.md`
- `docs/MAILING_QUEUE_SEND_GUARD_STAGE.md`

## Why this patch exists

The mail history UI had already been stabilized, but the lower mail queue layer still accepted partial or malformed arrays directly. Several legacy methods assumed that `$options`, `$row`, `emails_xml`, mail list rows, and queue rows were always complete arrays.

The most important risk was `itMailings::_send()`: it read from an undefined `$row` variable instead of the provided `$options` payload, and the method inserted the prepared mail row twice. That could produce warnings and duplicate queued mail records in paths that use the single-message queue helper.

## What changed

- Added small local option/row readers in the affected mail classes.
- Guarded `itMail` construction, field compilation, template replacement arrays, attachments, and push/push-all payloads.
- Guarded `itMailer` queue packet options and send-row reads.
- Skipped invalid queue rows that have no positive mail id or no recipient.
- Guarded mailing-list create/add/import/remove/list-name operations against missing `emails_xml`, missing `email`, and incomplete CSV rows.
- Reworked `itMailings::_send()` to use its `$options` payload and perform a single insert.
- Guarded `itMailings::_send_arr()` against malformed row payloads and empty recipients.
- Guarded `itMailings::_run()` for LIST/PRO/CHAT mailing targets against missing classes, empty user/list rows, and empty generated mail arrays.
- Guarded `_count_wait()`, `_stats()`, and `_strip_logo()` against incomplete DB result rows or null text.

## Boundaries preserved

This patch does not change:

- bootstrap/config/env behavior;
- routes or public entrypoint names;
- `.htaccess`;
- DB schema;
- storage format;
- mail table names;
- existing mail status names;
- existing public/admin mail operation names.

## Removed or changed legacy behavior

No files were deleted.

One defective duplicate insert in `itMailings::_send()` was removed by replacing the old two-insert flow with a single insert plus the existing `[MAILID]` correction update when the predicted id differs from the real inserted id. This is a bug fix in the same mail queue method, not a storage/schema change.

## Suggested runtime checks

After applying the patch, check:

- `/mailing/` page renders without PHP warnings in console/AJAX responses;
- sending a contact/order/buy form still creates one queued mail entry;
- queue processing does not fatal on partial rows;
- mail spam/delete/restore actions from the history modal still work;
- Apache/PHP logs contain no new `Undefined array key`, `Undefined variable`, or duplicate insert mail errors in mail classes.
