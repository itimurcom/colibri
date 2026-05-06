# M0 / P95 user auth / session guard stabilization bundle

## Scope

This stage continues the late M0 runtime stabilization line with a larger but still bounded vertical slice around legacy user authentication, session checks, social login, login/logout entrypoints, customer profile helpers, and session message queues.

## Why this exists

After the catalog, mailing, theme, schema, and public UI guard stages, the next shared risk was the user/auth boundary. Several helpers still assumed fully initialized `$_USER`, valid session hash payloads, complete DB rows, complete social-provider configs, and well-formed `$_SESSION['error']` rows. Those assumptions can leak warnings into public HTML, login redirects, AJAX responses, or social-login callbacks.

## Changed areas

- `SKEL80/classes/user/itUser.class.php`
  - normalizes constructor options;
  - initializes runtime state;
  - guards session hash/session row reads;
  - guards user row helper methods against missing DB rows;
  - avoids unsafe session close/update calls when user/session IDs are empty.

- `SKEL80/classes/user/itUserReg.class.php`
  - guards social request/provider reads;
  - declares the legacy `table_name` runtime property;
  - validates social-provider config before building token requests;
  - guards social callback/userdata storage against missing `$_USER`, missing login, and incomplete provider payloads.

- `public/login.php`, `public/logout.php`, `public/soclogin.php`
  - guards entrypoint request/session/runtime reads;
  - avoids direct user-runtime method calls when `$_USER` is not initialized;
  - keeps the existing redirect/login/logout behavior.

- `SKEL80/events/user/get_login_event.func.php`
  - guards optional login modal options and login request values.

- `public/engine/core/units/users/engine_customers.php`
  - guards customer request reads;
  - normalizes phone input before filtering;
  - escapes values injected into the generated JS user-data replacement snippet;
  - guards `update_userdata_script()` against missing user runtime.

- `public/engine/core/units/users/events/engine_customer_events.php`
  - centralizes customer user-runtime checks;
  - guards login/profile/pin flows against missing `$_USER`.

- `SKEL80/kernel/events/messages/add_error_message.func.php`
- `SKEL80/kernel/events/messages/add_service_message.func.php`
  - normalizes the legacy `$_SESSION['error']` queue;
  - avoids warnings from malformed message rows before duplicate checks.

## Boundaries kept

This patch does not change:

- bootstrap/config/env behavior;
- routes;
- `.htaccess`;
- DB schema;
- storage format;
- public entrypoint names;
- social provider names;
- existing login/logout URL behavior.

## Verification

Minimum verification performed for this bundle:

- `php -n -l` for all changed PHP files;
- test application of the ZIP over the current P94 base;
- `unzip -t` for the generated patch archive.

Suggested runtime smoke tests after applying:

- open `/login.php` flow through a normal login form;
- logout through `/logout.php`;
- open `/en/register/` and `/en/register/pin/`;
- verify cabinet/profile edit button still renders;
- test social-login button generation if configured;
- confirm PHP logs stay clean from `Undefined array key` / `Trying to access array offset on value of type null` in the touched files.
