# M0 / P63 buy/cabinet/friends controller cleanup bundle

## Goal
Clean project-side controller runtime for the remaining customer-facing controllers without changing routes, form ids, session keys, or shared-core behavior.

## Runtime scope
Changed files:
- `public/mvc/controllers/controller.buy.php`
- `public/mvc/controllers/controller.cabinet.php`
- `public/mvc/controllers/controller.friends.php`

## What changed
- Buy controller now has local helpers for:
  - selected item id resolution;
  - selected item preview rendering;
  - buy form construction;
  - buy form content rendering;
  - thank-you content rendering.
- Cabinet controller now has one local content assembly helper and no longer carries old commented user-mail feed code.
- Friends controller now has local helpers for feed construction and content rendering, with safe title initialization when the source content row is missing.

## What intentionally did not change
- routes are unchanged;
- form id `FORM2_BUY` is unchanged;
- submit operation `buy` is unchanged;
- thank-you session key `thankyoubuy` is unchanged;
- feed name `friends` is unchanged;
- shared-core classes were not changed;
- no new runtime files or folders were added.

## Manual checks
After applying:
- open `/buy/` with and without a selected item;
- submit buy form and check redirect to `/buy/thankyou/`;
- open cabinet as logged-in user;
- open cabinet as guest and verify redirect;
- open friends page and check admin edit controls when logged in.
