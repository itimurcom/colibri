# M0 / P67 controller residual request stabilization bundle

## Goal
Stabilize the remaining project-side controller entrypoints after the recent controller cleanup chain.

## Runtime scope
Changed controller files:
- `public/mvc/controllers/controller.about.php`
- `public/mvc/controllers/controller.shop.php`
- `public/mvc/controllers/controller.buy.php`
- `public/mvc/controllers/controller.order.php`
- `public/mvc/controllers/controller.contacts.php`
- `public/mvc/controllers/controller.measurement.php`
- `public/mvc/controllers/controller.register.php`

## What changed
- Removed the obsolete commented legacy feed block from the about controller.
- Added a safe `view` guard in the shop controller before reading `$cat_more[...]`.
- Added a safe `view` guard in the buy controller thank-you branch.
- Removed unused `_redata()` from the order controller.
- Fixed the order submit branch to read `$_REQUEST['op']` directly instead of using the removed local `$data` value.
- Removed stale non-executable controller comments and debug leftovers.

## What did not change
- Routes were not changed.
- Form ids were not changed.
- Submit operation names were not changed.
- Thank-you routes were not changed.
- Mail sending flow was not changed.
- No shared-core code was touched.
- No new runtime files or layers were added.

## Manual checks
After applying, check these pages:
- `/about/`
- `/shop/`
- `/buy/`
- `/buy/thankyou/`
- `/order/`
- `/contacts/`
- `/measurement/`
- `/register/`
