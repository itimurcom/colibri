# M0 / P25 engine_mails local duplicate reduction

## Goal
Reduce repeated request/build/send/render branches in `public/engine/core/engine_mails.php` without adding a new architecture layer.

## What changed
- `send_colibri_mails(...)` now uses local profile/body helpers instead of repeating form-specific mail setup inline.
- Mailing-history client-email extraction and subject decoration were separated into small local helpers in the same file.
- Mailing-history tabs are built directly without an intermediate `$panels` array.

## Important preserved boundaries
- The file remains a direct project runtime file.
- No new runtime directories.
- No namespace rollout.
- No dispatcher/controller/registry layer.
- No route or entrypoint changes.

## Corrective bugfix included in scope
The legacy variable typo `subject_of_user` was normalized to the actual user-subject source used by `send_colibri_mails(...)`.

## Intentional removals
- Removed the unused local variable `$str_of_user` from `mailtemplate_script(...)`.
- Removed duplicated inline request/body construction branches after they were replaced with local helpers.

## Result
The file becomes shorter and easier to trace as:
1. mail profile
2. request rows
3. user/admin body build
4. mail send
5. history panel render
