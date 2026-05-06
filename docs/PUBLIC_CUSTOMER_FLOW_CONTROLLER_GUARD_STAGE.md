# M0 / P90 public customer flow controller guard stabilization bundle

## Scope

This stage stabilizes public customer/account flow controllers and the mobile/cellular menu boundary after the form submit and reCaptcha guard pass.

Changed areas:

- `public/mvc/controllers/controller.buy.php`
- `public/mvc/controllers/controller.contacts.php`
- `public/mvc/controllers/controller.order.php`
- `public/mvc/controllers/controller.measurement.php`
- `public/mvc/controllers/controller.register.php`
- `public/mvc/controllers/controller.register.pin.php`
- `public/mvc/controllers/controller.cabinet.php`
- `public/mvc/controllers/controller.shop.php`
- `public/engine/core/engine_cellular.php`
- `public/engine/core/units/users/events/engine_admin_events.php`

## What changed

- Replaced fragile direct request reads in customer-facing controllers with local guarded request helpers.
- Added object/user guards before calling `$_USER->is_logged()` in account, registration, order, contact, buy, and measurement flows.
- Guarded form editor title rendering against missing `title_xml` and non-array editor data.
- Guarded item/category row usage in order/contact/buy flows so incomplete item rows do not leak warnings into HTML.
- Hardened measurement link decoding so broken encrypted `key` payloads fall back to the existing measurement error path instead of creating partial runtime data.
- Fixed the measurement admin hidden input markup from `val` to `value` so selected measurement form IDs are submitted consistently.
- Applied the actual mobile/cellular menu request and row guards in `engine_cellular.php`, matching the intended P88 boundary behavior in code, not only documentation.

## Compatibility notes

- No route changes.
- No DB schema changes.
- No storage format changes.
- No public entrypoint changes.
- No bootstrap/config/env changes.
- No files were removed.
- Existing controller names and legacy form actions are preserved.

## Manual smoke targets

- `/en/buy/` with and without `rec_id` / `selected_id`.
- `/en/order/` with and without `rec_id`.
- `/en/contacts/` with and without `rec_id`.
- `/en/measurement/` with a valid generated key and a broken key.
- `/en/register/` and `/en/register/pin/`.
- `/en/cabinet/` as logged-in and anonymous user.
- Mobile/cellular menu render on a normal public page.
