# M0 / P64 recent controller regression stabilization bundle

## Goal
Stabilize the recently changed project-side controller layer after the P59-P63 cleanup sequence.

## Runtime scope
Changed files:
- `public/mvc/controllers/controller.buy.php`
- `public/mvc/controllers/controller.cabinet.php`
- `public/mvc/controllers/controller.friends.php`

## What changed
- `controller.friends.php`
  - Added missing `global $_USER` inside `friends_controller_content(...)`.
  - This prevents the admin-panel condition from using an undefined local `$_USER` inside the function.

- `controller.buy.php`
  - Removed unused `itEditor::_redata()` assignment from the controller body.

- `controller.cabinet.php`
  - Removed unused `itEditor::_redata()` assignment from the controller body.
  - Replaced `$_CONTENT['content'] .= ...` with direct assignment because this controller builds its content once.

## Why this patch exists
The current project archive was provided without runtime verification after the previous cleanup patches. This patch is intentionally small and stabilizing: it fixes an obvious controller regression risk and avoids another large rewrite before manual checks.

## Preserved behavior
- Routes are unchanged.
- Controller names are unchanged.
- Public helper names are unchanged.
- Customer/auth flow is unchanged.
- Buy form flow is unchanged.
- Friends feed flow is unchanged.
- No new runtime files were added.
- No files were deleted.
