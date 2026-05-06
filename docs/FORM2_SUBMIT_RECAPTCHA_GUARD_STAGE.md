# M0 / P89 form2 submit / recaptcha guard stabilization bundle

## Scope

This stage stabilizes the legacy Form2 submit/render boundary after the request/session/feed/mail guard work in P86-P88.

Touched files:

- `SKEL80/classes/f2/itForm2.class.php`
- `SKEL80/events/f2/_check_v3reCapcha.func.php`
- `PROJECT_PATCHLOG.md`
- `docs/FORM2_SUBMIT_RECAPTCHA_GUARD_STAGE.md`

## What changed

- Added local guarded helpers inside `itForm2` for request reads, session-array reads, field-row reads, and field-row normalization.
- Guarded `f2hash` and reCaptcha score rendering so absent request/session keys do not leak warnings into public form output.
- Replaced the legacy missing-field debug stop (`var_dump` / `print_rr` / `die`) with safe field element fallback generation.
- Guarded required-field validation against missing `name`, `element`, `kind`, `required`, and option-array metadata.
- Hardened `_result_info()` so mail/order result rendering tolerates incomplete form rows and absent request values.
- Hardened `_reCaptcha()` so it does not warn on missing `v3resp`, empty secret values, failed remote requests, or broken JSON responses.
- Fixed the old `$_SESSION['v3cheked']` typo in `_check_v3reCaptcha()` and normalized score fallback handling.
- Replaced the `_check_value()` debug `print_r()/die` path for array option values with a safe localized-value fallback.

## Preserved behavior

This patch intentionally does not change:

- bootstrap/config/env behavior;
- routes or public entrypoint names;
- database schema;
- storage format;
- valid Form2 field semantics;
- mail/order/customer business logic.

## Runtime rationale

Form2 is used by public and admin/customer forms. Warnings or debug aborts in this class can break HTML, AJAX responses, and mail/order result rendering. This stage only adds local guards and safe fallbacks around optional or malformed payloads.

## Verification

Recommended checks after applying:

- open contacts/order/register/settings forms;
- submit an empty required form and confirm focus/error display still works;
- submit a valid contact/order form and confirm result info/mail summary still renders;
- verify that recaptcha-disabled environments do not warn;
- check PHP/Apache logs for `Undefined array key`, `Trying to access array offset on value of type null`, and `v3cheked`.
