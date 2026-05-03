# M0 / P61 order/contact controller runtime cleanup bundle

## Goal
Clean up project-side MVC controller runtime for order/contact/measurement/register flows after the customer/auth and admin/social cleanup passes.

## Runtime scope
Changed files:
- `public/mvc/controllers/controller.contacts.php`
- `public/mvc/controllers/controller.order.php`
- `public/mvc/controllers/controller.measurement.php`
- `public/mvc/controllers/controller.register.php`

## What changed
- Removed stale commented/dead controller fragments.
- Removed unreachable post-redirect content assembly in order/contact submit paths.
- Replaced duplicated register focus-error JavaScript with one local controller closure.
- Removed duplicated/unused local variables in order/contact/measurement controllers.
- Kept form ids, routes, submit operations, thank-you routes and session keys unchanged.

## What was not changed
- No new controller layer was added.
- No route names were changed.
- No form ids were changed.
- No mail send functions were changed.
- No `itForm2`, `itEditor`, `itMySQL`, or feed/runtime shared core was changed.
- No files were deleted.

## Manual checks
After applying the patch, check:
- `/contacts/` submit and `/contacts/thankyou/`;
- `/order/` submit and `/order/thankyou/`;
- `/measurement/` flow and `/measurement/thankyou/`;
- `/register/` duplicate email/phone validation messages;
- `/register/` login with non-registered email error message.
