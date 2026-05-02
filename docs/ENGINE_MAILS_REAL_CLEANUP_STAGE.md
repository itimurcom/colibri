# M0 / P48 engine_mails real cleanup bundle

## Goal
Clean the project-side mail runtime without introducing a mail framework, changing SMTP/msmtp configuration, or touching shared editor/form/feed code.

## Runtime scope
Changed file:
- `public/engine/core/engine_mails.php`

## What changed
- Mail template rendering now goes through one local helper: `get_colibri_mail_template_result(...)`.
- Mail history modal hidden forms now use one local helper: `get_mailing_history_action_form(...)`.
- Admin item-articul link in outgoing admin mails now uses runtime host via `CMS_CURRENT_BASE_URL` instead of the debug/server constant.
- Fixed the inline CSS typo `text-aling` → `text-align` in the admin mail articul block.
- Kept the user/admin mail sending path and mailing history UI in the same file.

## What did not change
- No SMTP/msmtp settings were changed.
- No mail transport class was changed.
- No public function names were removed or renamed.
- No new runtime files, folders, registries, controllers, or namespaces were added.
- No files were deleted.

## Test focus
After applying the patch, check:
- contacts form mail preview/result;
- order/buy form admin email;
- measurement form admin email;
- admin email with `articul` replacement;
- mail history tabs;
- mail preview modal;
- spam/remove/restore buttons in mail history modal.
