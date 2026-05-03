# M0 / P65 form page controllers request guard cleanup bundle

## Goal
Stabilize recent project-side form page controllers after the customer/admin/controller cleanup passes without changing routes, form ids, submit operation names, or shared-core behavior.

## Runtime scope
Changed files:
- `public/mvc/controllers/controller.contacts.php`
- `public/mvc/controllers/controller.order.php`
- `public/mvc/controllers/controller.measurement.php`
- `public/mvc/controllers/controller.register.php`
- `public/mvc/controllers/controller.register.pin.php`

## What changed
- Replaced direct `$_REQUEST['view']` checks with local `ready_val(...)` view variables.
- Replaced direct optional `$_REQUEST` reads in item/order/contact/measurement/register guards with `ready_val(...)` where the parameter can be absent.
- Removed unused `_redata()` reads from controllers where the result was not used.
- Removed unused register settings global.
- Removed a stale order-page image placeholder that no longer had a producer.

## Preserved behavior
- Routes are unchanged.
- Form ids are unchanged.
- Submit operation names are unchanged.
- Thank-you routes are unchanged.
- Mail sending flow is unchanged.
- `itForm2`, `itEditor`, `itMySQL`, feed/catalog runtime and shared-core were not changed.

## Why this is safe
This patch only removes unused controller-local state and guards optional request keys that may be absent on normal page loads. It does not rewrite controller flow or form submission behavior.
